'use strict'

const Lucid = use('Lucid')

class ApproveToken extends Lucid {

  member () {
    return this.belongsTo('App/Model/Member')
  }
}

module.exports = ApproveToken
