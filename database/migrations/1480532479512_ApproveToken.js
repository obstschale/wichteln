'use strict'

const Schema = use('Schema')

class ApproveTokensTableSchema extends Schema {

  up () {
    this.create('approve_tokens', (table) => {
      table.increments()
      table
        .integer('member_id')
        .unsigned()
        .references('id')
        .inTable('members')
      table.string('token', 50)
      table.timestamps()
    })
  }

  down () {
    this.drop('approve_tokens')
  }

}

module.exports = ApproveTokensTableSchema
