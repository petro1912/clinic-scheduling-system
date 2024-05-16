import admin from './admin'
import appAndPages from './app-and-pages'
import charts from './charts'
import dashboard from './dashboard'
import doctor from './doctor'
import forms from './forms'
import others from './others'
import patient from './patient'
import uiElements from './ui-elements'

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
  ...userRoutes, 
  ...appAndPages, 
  ...dashboard, 
  ...uiElements, 
  ...forms, 
  ...charts, 
  ...others,
]
