'use strict'

const Schema = use('Schema')

class MembersTableSchema extends Schema {

  up () {
    this.create('members', (table) => {
      table.increments()
      table
        .integer('group_id')
        .unsigned()
        .references('id')
        .inTable('groups')
      table.integer('wichtel_id')
      table.string('name')
      table.string('email')
      table.string('wishlist', 1000)
      table.string('status')
      table.string('token', 40)
      table.timestamps()
    })
  }

  down () {
    this.drop('members')
  }

}

module.exports = MembersTableSchema
