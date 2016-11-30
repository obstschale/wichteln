'use strict'

const Member = use('App/Model/Member')
const ApproveToken = use('App/Model/ApproveToken')

class ApproveController {
  * index (request, response) {
    const get = request.get()

    const token = yield ApproveToken
      .query()
      .where('token', get.token)
      .with('member')
      .first()

    const member = yield token.member().fetch()

    member.status = 'approved'

    yield member.save()

    response.ok({
      'status': 200,
      'mesage': 'You approved your participation.',
    })
  }
}

module.exports = ApproveController
