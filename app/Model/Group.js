'use strict'

const Lucid = use('Lucid')

class Group extends Lucid {

  static get rules () {
    return {
      name: 'required|string',
      wichtel_date: 'required|date_format:YYYY-MM-DD'
    }
  }

  members() {
    return this.hasMany('App/Model/Member')
  }

  apiTokens () {
    return this.hasMany('App/Model/Token')
  }

}

module.exports = Group
