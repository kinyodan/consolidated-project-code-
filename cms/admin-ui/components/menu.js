export const menuItems = [
  {
    id: 1,
    label: "menuitems.menu.text",
    isTitle: true
  },
  {
    id: 2,
    label: "menuitems.dashboard.text",
    icon: "uil-home-alt",
    badge: {
      variant: "info",
      text: "menuitems.dashboard.badge"
    },
    link: "/"
  },
  {
    id: 3,
    label: "menuitems.subject_management.text",
    isTitle: true
  },
  {
    id: 4,
    label: "Manage Clusters",
    icon: "uil-book-open",
    badge: {
      variant: "info",
      text: "menuitems.dashboard.badge"
    },
    link: "/subject-management/manage-clusters"
  },
  {
    id: 4,
    label: "Subject Requirements",
    icon: "uil-book-open",
    badge: {
      variant: "info",
      text: "menuitems.dashboard.badge"
    },
    link: "/subject-management/subject-display"
  },
  {
    id: 5,
    label: "Manage Subjects",
    icon: "uil-book-open",
    badge: {
      variant: "info",
      text: "menuitems.dashboard.badge"
    },
    link: "/subject-management/manage-subjects"
  },
  {
    id: 6,
    label: "Manage Education types",
    icon: "uil-book-open",
    badge: {
      variant: "info",
      text: "menuitems.dashboard.badge"
    },
    link: "/subject-management/manage-education-types"
  },
  {
    id: 7,
    label: "Subject Clustering",
    icon: "uil-book-open",
    badge: {
      variant: "info",
      text: "menuitems.dashboard.badge"
    },
    link: "/subject-management/subject-clusters"
  },
  
  /*{
    id: 3,
    label: "menuitems.users.text",
    isTitle: true
  },

  {
    id: 4,
    label: "menuitems.roles.list",
    icon: "uil-user-exclamation",
    link: "/roles/roles-list"
  },
  {
    id: 5,
    label: "menuitems.groups.list",
    icon: "uil-user-circle",
    link: "/groups/groups-list"
  },
  {
    id: 6,
    label: "menuitems.users.list",
    icon: "uil-users-alt",
    link: "/users/users-list"
  },
  */
  {
    id: 8,
    label: "menuitems.course_management.text",
    isTitle: true
  },
  {
    id: 9,
    label: "menuitems.course_management.list.Course-pathway-linking",
    icon: "uil-book-open",
    link: "/course-management/course-pathway-linking"
  },
  {
    id: 10,
    label: "menuitems.course_management.list.institutions",
    icon: "uil-graduation-hat",
    subItems: [{
      id: 11,
      label: "menuitems.course_management.list.institutions",
      icon: "uil-graduation-hat",
      link: "/course-management/institution-list"
    },
      {
        id: 12,
        label: "menuitems.course_management.list.alumni",
        icon: "uil-users-alt",
        link: "/course-management/alumni"
      },
    ]

  },

  {
    id: 13,
    label: "menuitems.course_management.list.courses",
    icon: "uil-book-open",
    link: "/course-management/courses-list"
  },
  {
    id: 14,
    label: "Pathways",
    icon: "uil-graduation-hat",
        subItems: [{
           id: 15,
           label: "Settings",
           icon: "uil-graduation-hat",
              subItems: [{
                id: 16,
                label: "Employer Types",
                icon: "uil-graduation-hat",
                link: "/pathways/employer_types/employer-types"
                },
                {
                  id: 17,
                  label: "Eligibility Types",
                  icon: "uil-users-alt",
                  link: "/pathways/eligibility_types/eligibility-types"
                },
                 
              ]
         },
         {
           id: 18,
           label: "Employers",
           icon: "uil-graduation-hat",
           link: "/pathways/employers/employers"
          },
         {
           id: 19,
           label: "Pathways ",
           icon: "uil-users-alt",
           link: "/pathways/pathways/pathways-list"
          },         
         {
           id: 20,
           label: "Specializations",
           icon: "uil-users-alt",
           link: "/pathways/specializations/specializations"
          },
          {
           id: 21,
           label: "Eligibilities",
           icon: "uil-users-alt",
           link: "/pathways/eligibilities/eligibilities"
          },        
      ]

  },
  /*{
      id: 6,
      label: "menuitems.ecommerce.text",
      icon: "uil-store",
      subItems: [
          {
              id: 7,
              label: "menuitems.ecommerce.list.products",
              link: "/ecommerce/products",
              parentId: 6
          },

      ]
  },*/
];

