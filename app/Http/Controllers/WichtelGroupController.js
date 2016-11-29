'use strict'

const Group = use('App/Model/Group')

class WichtelGroupController {

  * index (request, response) {
    response.forbidden({
      'status': 403,
    })
  }

  * show (request, response) {
    const group = yield Group.find(request.param('id'))

    if (group === null) {
      return response.notFound({
        'status': 404,
      })
    }

    response.ok({
      'status': 200,
      'data': group,
    })
  }

  * store (request, response) {
    const data = request.only('name', 'wichtel_date')

    // TODO: Validation

    const group = yield Group.create(data);

    response.created({
      'status': 201,
      'data': group,
    })
  }

  * update (request, response) {
    const group = yield Group.find(request.param('id'))

    if (group === null) {
      return response.notFound({
        'status': 404,
      })
    }

    const data = request.only('name', 'wichtel_date')

    // TODO: Validation

    group.name = data.name
    group.wichtel_date = data.wichtel_date
    yield group.save()

    response.ok({
      'status': 200,
      'data': group,
    })
  }

  * destroy (request, response) {
    const group = yield Group.find(request.param('id'));

    if (group === null) {
      return response.notFound({
        'status': 404,
      })
    }

    yield group.delete();

    response.noContent();
  }
}

module.exports = WichtelGroupController
