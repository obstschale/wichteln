'use strict'

const Mail = use('Mail')
const Group = use('App/Model/Group')
const Member = use('App/Model/Member')

class WichtelMemberController {

  * index (request, response) {
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
    const group = yield Group.findOrFail(request.param('group'))
    const member = yield group
      .members()
      .where('id', request.param('id'))
      .fetch()

    // TODO: Add 404 if member is not found

    response.ok({
      'status': 200,
      'data': member,
    })
  }

  * store (request, response) {
    const group = yield Group.findOrFail(request.param('group'))

    const data = request.only('name', 'email', 'wishlist')

    // TODO: Validation

    const member = new Member()
    member.name = data.name
    member.email = data.email
    member.wishlist = data.wishlist

    yield group.members().save(member)

    yield Mail.send('emails.welcome', {name: member.name}, (message) => {
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
    const member = yield Member.findOrFail(request.param('id'))

    if (member.group_id !== Number(request.param('group'))) {
      return response.unauthorized({
        'status': 403,
      })
    }

    yield member.delete()

    response.noContent()
  }
}

module.exports = WichtelMemberController
