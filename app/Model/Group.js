'use strict'

const Lucid = use('Lucid')

class Group extends Lucid {

  members() {
    return this.hasMany('App/Model/Member')
  }

}

module.exports = Group
