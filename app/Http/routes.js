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
Route.on('/docs').render('docs')
Route.get('/approve', 'ApproveController.index')

Route.group('v1', () => {

  Route.get('/', function * (request, response) {
    response.ok({
      'status': 200,
      'api': {
        'version': 1.0,
        'framework': 'AdonisJS',
      }
    })
  })

  Route
    .resource('/wichtelgroup', 'WichtelGroupController')
    .except('index', 'create', 'edit')

  Route
    .resource('/wichtelgroup/:group/wichtelmember', 'WichtelMemberController')
    .except('create', 'edit')

  Route.post('/wichteln', 'WichtelController.start')

}).prefix('/api/v1')
