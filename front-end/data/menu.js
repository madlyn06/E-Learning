export const menuItems = [
  {
    title: 'Home',
    isActive: true,
    subItems: [{ title: 'Home Page 01', href: '/' }]
  },
  {
    title: 'Courses',
    hasMega: true,
    subItems: [
      {
        title: 'Course List',
        subItems: [
          { title: 'Course Grid Basic', href: '/course-grid-basic' },
          { title: 'Course Grid Modern', href: '/course-grid-modern' },
          {
            title: 'Course Grid Left Sidebar',
            href: '/course-grid-left-sidebar'
          },
          {
            title: 'Course Grid Right Sidebar',
            href: '/course-grid-right-sidebar'
          },
          { title: 'Course List Sidebar', href: '/course-list-sidebar' },
          { title: 'Course All List Style', href: '/all-list-style' }
        ]
      },
      {
        title: 'Course Single',
        subItems: [
          { title: 'Course Single 01', href: '/course-single-v1/1' },
          { title: 'Course Single 02', href: '/course-single-v2' },
          { title: 'Course Single 03', href: '/course-single-v3' },
          { title: 'Course Single 04', href: '/course-single-v4' },
          { title: 'Course Single 05', href: '/course-single-v5' }
        ]
      },
      {
        title: 'Course Category',
        subItems: [
          { title: 'Categories', href: '/categories' },
          { title: 'Online Business', href: '/categories' },
          { title: 'Photography', href: '/categories' },
          { title: 'Music & Audio', href: '/categories' },
          { title: 'Programming & Tech', href: '/categories' },
          { title: 'Graphics & Design', href: '/categories' }
        ]
      }
    ]
  },
  {
    title: 'Pages',
    isActive: true,
    subItems: [
      { title: 'Instructor List', href: '/instructor-list' },
      { title: 'Instructor Single', href: '/instructor-single/1' },
      { title: 'Become a Teacher', href: '/become-teacher' },
      { title: 'Event List', href: '/event-list' },
      { title: 'Event Single', href: '/event-single/1' },
      { title: 'About', href: '/about' },
      { title: 'Contact', href: '/contact' },
      { title: 'Help Center', href: '/help-center' },
      { title: 'Pricing', href: '/pricing' },
      { title: 'Faq', href: '/faq' },
      { title: 'Terms', href: '/terms' },
      { title: '404', href: '/page-not-found' },
      { title: 'Login', href: '/auth/login' },
      { title: 'Register', href: '/auth/register' },
      { title: 'Dashboard', href: '/dashboard' },
      { title: 'UI Elements', href: '/ui-elements' }
    ]
  },
  {
    title: 'Blog',
    subItems: [
      { title: 'Blog Grid', href: '/blog-grid' },
      { title: 'Blog List 01', href: '/blog-list-v1' },
      { title: 'Blog List 02', href: '/blog-list-v2' },
      { title: 'Blog Single', href: '/blog-single/1' }
    ]
  },
  {
    title: 'Shop',
    subItems: [
      { title: 'Shop List', href: '/shop-list' },
      { title: 'Shop Single', href: '/shop-single/1' },
      { title: 'Shop Cart', href: '/shop-cart' },
      { title: 'Shop Checkout', href: '/shop-checkout' },
      { title: 'Shop Order', href: '/shop-order' }
    ]
  }
]

export const categories = [
  {
    id: 1,
    title: 'Graphics & Design',

    subItems: [
      { id: 1, title: 'Human Resources', href: '/categories' },
      { id: 2, title: 'Operations', href: '/categories' },
      { id: 3, title: 'Supply Chain Management', href: '/categories' },
      { id: 4, title: 'Customer Service', href: '/categories' },
      { id: 5, title: 'Manufacturing', href: '/categories' },
      { id: 6, title: 'Health And Safety', href: '/categories' },
      { id: 7, title: 'Quality Management', href: '/categories' },
      { id: 8, title: 'E-commerce', href: '/categories' },
      { id: 9, title: 'Management', href: '/categories' },
      { id: 10, title: 'Sales', href: '/categories' }
    ]
  },
  {
    id: 2,
    title: 'Digital Marketing',

    subItems: [
      { id: 1, title: 'Human Resources', href: '/categories' },
      { id: 2, title: 'Operations', href: '/categories' },
      { id: 3, title: 'Supply Chain Management', href: '/categories' },
      { id: 4, title: 'Customer Service', href: '/categories' },
      { id: 5, title: 'Manufacturing', href: '/categories' },
      { id: 6, title: 'Health And Safety', href: '/categories' },
      { id: 7, title: 'Quality Management', href: '/categories' },
      { id: 8, title: 'E-commerce', href: '/categories' },
      { id: 9, title: 'Management', href: '/categories' },
      { id: 10, title: 'Sales', href: '/categories' }
    ]
  },
  {
    id: 3,
    title: 'Business',

    subItems: [
      { id: 1, title: 'Human Resources', href: '/categories' },
      { id: 2, title: 'Operations', href: '/categories' },
      { id: 3, title: 'Supply Chain Management', href: '/categories' },
      { id: 4, title: 'Customer Service', href: '/categories' },
      { id: 5, title: 'Manufacturing', href: '/categories' },
      { id: 6, title: 'Health And Safety', href: '/categories' },
      { id: 7, title: 'Quality Management', href: '/categories' },
      { id: 8, title: 'E-commerce', href: '/categories' },
      { id: 9, title: 'Management', href: '/categories' },
      { id: 10, title: 'Sales', href: '/categories' }
    ]
  },
  {
    id: 4,
    title: 'Music & Audio',

    subItems: [
      { id: 1, title: 'Human Resources', href: '/categories' },
      { id: 2, title: 'Operations', href: '/categories' },
      { id: 3, title: 'Supply Chain Management', href: '/categories' },
      { id: 4, title: 'Customer Service', href: '/categories' },
      { id: 5, title: 'Manufacturing', href: '/categories' },
      { id: 6, title: 'Health And Safety', href: '/categories' },
      { id: 7, title: 'Quality Management', href: '/categories' },
      { id: 8, title: 'E-commerce', href: '/categories' },
      { id: 9, title: 'Management', href: '/categories' },
      { id: 10, title: 'Sales', href: '/categories' }
    ]
  },
  {
    id: 5,
    title: 'Data',

    subItems: [
      { id: 1, title: 'Human Resources', href: '/categories' },
      { id: 2, title: 'Operations', href: '/categories' },
      { id: 3, title: 'Supply Chain Management', href: '/categories' },
      { id: 4, title: 'Customer Service', href: '/categories' },
      { id: 5, title: 'Manufacturing', href: '/categories' },
      { id: 6, title: 'Health And Safety', href: '/categories' },
      { id: 7, title: 'Quality Management', href: '/categories' },
      { id: 8, title: 'E-commerce', href: '/categories' },
      { id: 9, title: 'Management', href: '/categories' },
      { id: 10, title: 'Sales', href: '/categories' }
    ]
  },
  {
    id: 6,
    title: 'Video & Animation',

    subItems: [
      { id: 1, title: 'Human Resources', href: '/categories' },
      { id: 2, title: 'Operations', href: '/categories' },
      { id: 3, title: 'Supply Chain Management', href: '/categories' },
      { id: 4, title: 'Customer Service', href: '/categories' },
      { id: 5, title: 'Manufacturing', href: '/categories' },
      { id: 6, title: 'Health And Safety', href: '/categories' },
      { id: 7, title: 'Quality Management', href: '/categories' },
      { id: 8, title: 'E-commerce', href: '/categories' },
      { id: 9, title: 'Management', href: '/categories' },
      { id: 10, title: 'Sales', href: '/categories' }
    ]
  },
  {
    id: 7,
    title: 'Photography',

    subItems: [
      { id: 1, title: 'Human Resources', href: '/categories' },
      { id: 2, title: 'Operations', href: '/categories' },
      { id: 3, title: 'Supply Chain Management', href: '/categories' },
      { id: 4, title: 'Customer Service', href: '/categories' },
      { id: 5, title: 'Manufacturing', href: '/categories' },
      { id: 6, title: 'Health And Safety', href: '/categories' },
      { id: 7, title: 'Quality Management', href: '/categories' },
      { id: 8, title: 'E-commerce', href: '/categories' },
      { id: 9, title: 'Management', href: '/categories' },
      { id: 10, title: 'Sales', href: '/categories' }
    ]
  },
  {
    id: 8,
    title: 'Lifestyle',

    subItems: [
      { id: 1, title: 'Human Resources', href: '/categories' },
      { id: 2, title: 'Operations', href: '/categories' },
      { id: 3, title: 'Supply Chain Management', href: '/categories' },
      { id: 4, title: 'Customer Service', href: '/categories' },
      { id: 5, title: 'Manufacturing', href: '/categories' },
      { id: 6, title: 'Health And Safety', href: '/categories' },
      { id: 7, title: 'Quality Management', href: '/categories' },
      { id: 8, title: 'E-commerce', href: '/categories' },
      { id: 9, title: 'Management', href: '/categories' },
      { id: 10, title: 'Sales', href: '/categories' }
    ]
  },
  {
    id: 9,
    title: 'Writing & Translation',

    subItems: [
      { id: 1, title: 'Human Resources', href: '/categories' },
      { id: 2, title: 'Operations', href: '/categories' },
      { id: 3, title: 'Supply Chain Management', href: '/categories' },
      { id: 4, title: 'Customer Service', href: '/categories' },
      { id: 5, title: 'Manufacturing', href: '/categories' },
      { id: 6, title: 'Health And Safety', href: '/categories' },
      { id: 7, title: 'Quality Management', href: '/categories' },
      { id: 8, title: 'E-commerce', href: '/categories' },
      { id: 9, title: 'Management', href: '/categories' },
      { id: 10, title: 'Sales', href: '/categories' }
    ]
  },
  {
    id: 10,
    title: 'Programming & Tech',

    subItems: [
      { id: 1, title: 'Human Resources', href: '/categories' },
      { id: 2, title: 'Operations', href: '/categories' },
      { id: 3, title: 'Supply Chain Management', href: '/categories' },
      { id: 4, title: 'Customer Service', href: '/categories' },
      { id: 5, title: 'Manufacturing', href: '/categories' },
      { id: 6, title: 'Health And Safety', href: '/categories' },
      { id: 7, title: 'Quality Management', href: '/categories' },
      { id: 8, title: 'E-commerce', href: '/categories' },
      { id: 9, title: 'Management', href: '/categories' },
      { id: 10, title: 'Sales', href: '/categories' }
    ]
  }
]
