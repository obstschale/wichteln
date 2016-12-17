'use strict'

const Env = use('Env')
const Lucid = use('Lucid')

class Member extends Lucid {

  static get rules () {
    return {
      name: 'required|string',
      email: 'required|email',
      wishlist: 'string',
    }
  }

  group () {
    return this.belongsTo('App/Model/Group')
  }

  approveToken () {
    return this.hasOne('App/Model/ApproveToken')
  }

  * getVerifyLink() {
    const data = {
      'token': Math.random().toString(36).substring(2)
    }

    const approveToken = yield this.approveToken().create(data)

    return Env.get('APP_URL') + `/approve?token=${approveToken.token}`
  }
}

module.exports = Member
