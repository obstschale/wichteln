'use strict'

const Schema = use('Schema')

class GroupsTableSchema extends Schema {

  up () {
    this.create('groups', (table) => {
      table.increments()
      table.timestamps()
      table.string('name')
      table.date('wichtel_date')
    })
  }

  down () {
    this.drop('groups')
  }

}

module.exports = GroupsTableSchema
