import admin from './admin'
import doctor from './doctor'
import patient from './patient'

const userData = JSON.parse(localStorage.getItem('userData') || '{}')
const userRole = (userData && userData.role) ? userData.role : null

let userRoutes
switch (userRole) {
case 'admin':
  userRoutes = [...admin, ...doctor]
  break
case 'doctor':
  userRoutes = [...doctor]
  break
case 'patient':
  userRoutes = [...patient]
}

export default [
  ...userRoutes
]
