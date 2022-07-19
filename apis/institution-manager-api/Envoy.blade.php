@servers(['test-server-one' => 'deployer@165.22.218.168'])

@setup
    $repository = 'git@gitlab.com:cradle-app/institution-manager/institution-manager-api.git';
    $app_dir = '/var/www/institutions-manager-api';
    $super_config_source = '/var/www/institutions-manager-api/supervisor/institution-manager.conf';
    $super_config_destination = '/etc/supervisor/conf.d/';
    $db_file = '/var/lib/mysql/craydle_institution_manager';
@endsetup

@story('deploy_on_test')
    {{--install_server_dependencies--}}
    pull_repository_on_test
    move_env_file
    create_database_if_it_doesnt_exist
    run_composer_on_test
    run_migrations
    seed_database
    cleanup_deployment
    install_supervisor
    enable_vhost
@endstory

@task('install_server_dependencies')
    echo "Installing server dependencies"
    sudo apt install php-curl php-deepcopy php-gd php-geoip php-geos php-image-text php-imagick php-json php-mbstring php-memcache php-memcached php-mysql php-net-socket php-numbers-words php-xml zip jpegoptim optipng pngquant gifsicle -y
    echo "Installed server dependencies"
    sudo apt-get --assume-yes install php-redis
    echo "Installed php-redis"
@endtask

@task('pull_repository_on_test')
    echo 'Create the APP directory'
    [ ! -d "{{ $app_dir }}" ] && mkdir -p {{ $app_dir }}
    echo 'Change owner to deployer'
    sudo chown -R deployer:deployer {{ $app_dir }}
    cd {{ $app_dir }}
    echo 'Run GIT init if the repo is setup'
    [ ! -d ".git" ] && git init && git remote add origin {{ $repository }} && git fetch --all && git checkout development
    echo 'Pull the feature updates'
    git stash -q && git pull origin development
@endtask

@task('create_database_if_it_doesnt_exist')
    echo "Check if database exists and create it"
    sudo [ ! -d "/var/lib/mysql/craydle_institution_manager" ] && mysql -u craydle_db_user -pPjS@gW8S6pNdyxd -e "create database craydle_institution_manager;" || echo "Database exists"
@endtask

@task('move_env_file')
    echo 'Copy env.testing to .env'
    cd {{ $app_dir }}
    cp .env.testing .env
@endtask

@task('run_composer_on_test')
    echo "Running composer on test"
    cd {{ $app_dir }}
    composer install --prefer-dist --no-scripts -q -o
@endtask

@task("install_passport")
    echo "Install passport for authentication"
    cd {{ $app_dir }}
    php artisan passport:install
@endtask

@task("run_migrations")
    echo "Run migrations"
    cd {{ $app_dir }}
    php artisan migrate
@endtask

@task("seed_database")
    echo "seeding required data"
    cd {{ $app_dir }}
    php artisan db:seed
@endtask

@task("cleanup_deployment")
    echo "Clear cache & update permissions"
    cd {{ $app_dir }}
    php artisan cache:clear
    echo "Set owner"
    sudo chown -R deployer:www-data *
    echo "changed file owner"
    cd /var/www && sudo && sudo chmod -R 777 *
    echo "Changed file write permissions"
@endtask

@task("install_supervisor")
    sudo apt list --installed 2>/dev/null | grep 'supervisor' || sudo apt install 'supervisor'
    echo "Copy supervisor configuration file from {{ $super_config_source }} to {{ $super_config_destination }}"
    sudo cp -f {{ $super_config_source }} {{ $super_config_destination }}
    echo "Configuration file has been copied"
    sudo supervisorctl reread
    sudo supervisorctl reload
    echo "Done"
@endtask

@task('enable_vhost')
    cd {{ $app_dir }}
    #Check if the site is already enabled
    [[ ! -f /etc/apache2/sites-available/institutions.craydel.online.conf ]] && sudo cp deploy-scripts/institutions.craydel.online.conf /etc/apache2/sites-available/institutions.craydel.online.conf && sudo a2ensite institutions.craydel.online.conf && sudo  systemctl reload apache2
    #copy the files to activate the site
    echo "Vhost created."
@endtask
