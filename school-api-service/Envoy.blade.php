@servers(['test-server-one' => 'deployer@165.22.218.168'])

@setup
    $repository = 'git@gitlab.com:cradle-app/school/school-api-service.git';
    $app_dir = '/var/www/school-api-service';
    $super_config_source = '/var/www/school-api-service/supervisor/school-service.conf';
    $super_config_destination = '/etc/supervisor/conf.d/'
@endsetup

@story('deploy_on_test')
    pull_repository_on_test
    move_env_file
    create_database_if_it_doesnt_exist
    run_composer_on_test
    run_migrations
    cleanup_deployment
    seed_database
    install_supervisor
    enable_vhost
@endstory

@task('pull_repository_on_test')
    echo 'Pull files from repo'
    cd {{ $app_dir }}
    {{--git checkout development--}}
    [ ! -d ".git" ] && git init && git remote add origin {{ $repository }} && git fetch --all
    sudo git stash -q && git pull origin master
    echo 'Files have been updated.'
@endtask

@task('move_env_file')
    echo 'Copy env.testing to .env'
    cd {{ $app_dir }}
    cp .env.testing .env
@endtask

@task('create_database_if_it_doesnt_exist')
    echo "Check if database exists and create it"
    sudo [ ! -d "/var/lib/mysql/craydel_school_db" ] && mysql -u craydle_db_user -pPjS@gW8S6pNdyxd -e "create database craydel_school_db;" || echo "Database exists"
@endtask

@task('run_composer_on_test')
    echo "Running composer on test"
    cd {{ $app_dir }}
    echo "Remove the lock file and vendor folder"
    rm -f composer.lock
    rm -rf vendor
    composer install --ignore-platform-reqs --prefer-dist --no-scripts -q -o
    echo "Composer has installed"
    composer dump-autoload
    echo "Clear composer cache"
@endtask

@task("install_passport")
    echo "Install passport for authentication"
    cd {{ $app_dir }}
    php artisan passport:install
    php artisan passport:keys --force
@endtask

@task("run_migrations")
    echo "Run migrations"
    cd {{ $app_dir }}
    php artisan migrate
    echo "Migrated tables."
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
    sudo chmod -R 777 *
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
    [[ ! -f /etc/apache2/sites-available/school-api-service.craydel.online.conf ]] && sudo cp deploy-scripts/school-api-service.craydel.online.conf /etc/apache2/sites-available/school-api-service.craydel.online.conf && sudo a2ensite school-api-service.craydel.online.conf && sudo  systemctl reload apache2
    #copy the files to activate the site
    echo "Vhost created."
@endtask


