'use strict'

const Lucid = use('Lucid')

class Member extends Lucid {

  group () {
    return this.belongsTo('App/Model/Group')
  }
}

module.exports = Member
