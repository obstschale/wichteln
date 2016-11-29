'use strict'

const Schema = use('Schema')

class MembersTableSchema extends Schema {

  up () {
    this.create('members', (table) => {
      table.increments()
      table.timestamps()
      table.string('name')
      table.string('email')
      table.boolean('is_oberwichtel')
      table.string('wunschzettel', 1000)
    })
  }

  down () {
    this.drop('members')
  }

}

module.exports = MembersTableSchema
