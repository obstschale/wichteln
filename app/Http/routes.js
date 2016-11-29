'use strict'

/*
|--------------------------------------------------------------------------
| Router
|--------------------------------------------------------------------------
|
| AdonisJs Router helps you in defining urls and their actions. It supports
| all major HTTP conventions to keep your routes file descriptive and
| clean.
|
*/

const Route = use('Route')

Route.on('/').render('welcome')

Route.group('v1', () => {

  Route.resource('/wichtelgroup', 'WichtelGroupController')
  Route.resource('/wichtelgroup/:group/wichtelmember', 'WichtelMemberController')

}).prefix('/api/v1')
