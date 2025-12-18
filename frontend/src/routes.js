import React from 'react'

// Pages
const Dashboard = React.lazy(() => import('./views/dashboard/Dashboard'))
const Teams = React.lazy(() => import('./views/teams/Teams'))
const GenericCrawler = React.lazy(() => import('./views/generic/GenericCrawler'))

const routes = [
  { path: '/', exact: true, name: 'Home' },
  { path: '/dashboard', name: 'Dashboard', element: Dashboard },

  // App
  { path: '/teams', name: 'Times', element: Teams },
  { path: '/crawler', name: 'Crawler', element: GenericCrawler },
]

export default routes
