'use strict'

const Mail = use('Mail')
const Group = use('App/Model/Group')
const Member = use('App/Model/Member')

class WichtelMemberController {

  * index (request, response) {
    const isLoggedIn = yield request.auth.check()

    if (!isLoggedIn || request.auth.user.id !== Number(request.param('group'))) {
      return response.unauthorized({
        'status': 401,
        'message': 'Wrong token.',
      })
    }

    const group = yield Group
      .query()
      .where('id', request.param('group'))
      .with('members')
      .fetch()

    response.ok({
      'status': 200,
      'data': group,
    })
  }

  * show (request, response) {
    const isLoggedIn = yield request.auth.check()

    if (!isLoggedIn || request.auth.user.id !== Number(request.param('group'))) {
      return response.unauthorized({
        'status': 401,
        'message': 'Wrong token.',
      })
    }

    const group = yield Group.findOrFail(request.param('group'))

    const member = yield group
      .members()
      .where('id', request.param('id'))
      .fetch()

    response.ok({
      'status': 200,
      'data': member,
    })
  }

  * store (request, response) {
    const isLoggedIn = yield request.auth.check()

    if (!isLoggedIn || request.auth.user.id !== Number(request.param('group'))) {
      return response.unauthorized({
        'status': 401,
        'message': 'Wrong token.',
      })
    }

    const group = yield Group.findOrFail(request.param('group'))

    const data = request.only('name', 'email', 'wishlist')

    // TODO: Validation

    const member = new Member()
    member.name = data.name
    member.email = data.email
    member.wishlist = data.wishlist
    member.status = 'invited'

    yield group.members().save(member)

    yield Mail.send('emails.invite', {name: member.name}, (message) => {
      message.to(member.email, member.name)
      message.from('awesome@adonisjs.com')
      message.subject('Welcome to the Kitten\'s World')
    })

    response.created({
      'status': 201,
      'data': member,
    })
  }

  * update (request, response) {
    const isLoggedIn = yield request.auth.check()

    if (!isLoggedIn || request.auth.user.id !== Number(request.param('group'))) {
      return response.unauthorized({
        'status': 401,
        'message': 'Wrong token.',
      })
    }

    const member = yield Member.findOrFail(request.param('id'))

    if (member.group_id !== Number(request.param('group'))) {
      return response.unauthorized({
        'status': 403,
      })
    }

    const data = request.only('name', 'email', 'whishlist')

    // TODO: Validation

    member.name = data.name
    member.email = data.email
    member.wishlist = data.wishlist

    yield member.save()

    response.ok({
      'status': 200,
      'data': member,
    })
  }

  * destroy (request, response) {
    const isLoggedIn = yield request.auth.check()

    if (!isLoggedIn || request.auth.user.id !== Number(request.param('group'))) {
      return response.unauthorized({
        'status': 401,
        'message': 'Wrong token.',
      })
    }

    const member = yield Member.findOrFail(request.param('id'))

    if (member.group_id !== Number(request.param('group'))) {
      return response.forbidden({
        'status': 403,
        'message': 'Deleting a member, which is not in your group is not nice.'
      })
    }

    yield member.delete()

    response.noContent()
  }
}

module.exports = WichtelMemberController
