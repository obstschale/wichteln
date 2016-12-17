'use strict'

const Validator = use('Validator')
const Group = use('App/Model/Group')
const Token = use('App/Model/Token')
const Member = use('App/Model/Member')

class WichtelGroupController {

  * index (request, response) {
    response.methodNotAllowed({
      status: 405,
      error: "Methode Not Allowed",
    })
  }

  * show (request, response) {
    const isLoggedIn = yield request.auth.check()

    if (!isLoggedIn || request.auth.user.id !== Number(request.param('id'))) {
      return response.unauthorized({
        'status': 401,
        'message': 'Wrong token.',
      })
    }

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

    const validation = yield Validator.validate(data, Group.rules)

    if (validation.fails()) {
      response.json(validation.messages())
      return
    }

    const group = new Group();
    group.name = data.name
    group.wichtel_date = data.wichtel_date
    group.status = 'created'

    yield group.save()

    const token = yield request.auth.generate(group)

    response.created({
      'status': 201,
      'data': {
        group,
        'token': token.token,
      }
    })
  }

  * update (request, response) {
    const isLoggedIn = yield request.auth.check()

    if (!isLoggedIn || request.auth.user.id !== Number(request.param('id'))) {
      return response.unauthorized({
        'status': 401,
        'message': 'Wrong token.',
      })
    }

    const group = yield Group.find(request.param('id'))

    if (group === null) {
      return response.notFound({
        'status': 404,
        'message': 'Wrong token.',
      })
    }

    const data = request.only('name', 'wichtel_date')

    const validation = yield Validator.validate(data, Group.rules)

    if (validation.fails()) {
      response.json(validation.messages())
      return
    }

    group.name = data.name
    group.wichtel_date = data.wichtel_date
    yield group.save()

    response.ok({
      'status': 200,
      'data': group,
    })
  }

  * destroy (request, response) {
    const isLoggedIn = yield request.auth.check()

    if (!isLoggedIn || request.auth.user.id !== Number(request.param('id'))) {
      return response.unauthorized({
        'status': 401,
        'message': 'Wrong token.',
      })
    }

    const group = yield Group
      .query()
      .where('id', request.param('id'))
      .with('members')
      .first()

    if (group === null) {
      return response.notFound({
        'status': 404,
      })
    }

    const members = yield group.members().fetch()
    for (const m in members.value()) {
      console.log(members.value()[m])
      yield members.value()[m].delete()
    }

    const token = yield Token.findBy('group_id', group.id)
    yield token.delete()

    yield group.delete()

    response.noContent()
  }
}

module.exports = WichtelGroupController
