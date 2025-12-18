import React from 'react'
import CIcon from '@coreui/icons-react'
import { cilSpeedometer, cilList, cilSearch } from '@coreui/icons'

const _nav = [
  {
    component: 'CNavItem',
    name: 'Dashboard',
    to: '/dashboard',
    icon: <CIcon icon={cilSpeedometer} customClassName="nav-icon" />,
  },
  {
    component: 'CNavItem',
    name: 'Times',
    to: '/teams',
    icon: <CIcon icon={cilList} customClassName="nav-icon" />,
  },
  {
    component: 'CNavItem',
    name: 'Crawler',
    to: '/crawler',
    icon: <CIcon icon={cilSearch} customClassName="nav-icon" />,
  },
]

export default _nav
