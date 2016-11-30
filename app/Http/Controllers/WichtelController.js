'use strict'

const Group = use('App/Model/Group')
const Member = use('App/Model/Member')
const _ = require('lodash')

class WichtelController {

  * start (request, response) {
    // TODO: Fetch token and validate
    const data = request.only('group')

    const members = yield Member
      .query()
      .where('group_id', data.group)
      .fetch()

    let approved = _.filter(members.value(), function(m) {
      return (m.status === 'approved')
    });

    if (approved.length < 3) {
      return response.forbidden({
        'status': 403,
        'message': 'Too few people approved. At least 3 members are needed to start.'
      })
    }

    const assigned = this.findWichtelBuddy(approved)

    for (const member of assigned) {
      yield member.save()
    }

    response.ok({
      'status': 200,
      'message': 'Woho, our imps are on their way delivering the messages.'
    })

  }

  findWichtelBuddy (members) {
    this.shuffle(members)

    let assigned = _.map(members, (m, i) => {
      if (i === members.length -1) {
        m.wichtel_id = members[0].id
      } else {
        m.wichtel_id = members[i+1].id
      }

      return m
    })

    return assigned
  }

  /**
  * Fisher-Yates Shuffle
  * https://en.wikipedia.org/wiki/Fisher%E2%80%93Yates_shuffle
  */
  shuffle(array) {
    let counter = array.length;

    // While there are elements in the array
    while (counter > 0) {
      // Pick a random index
      let index = Math.floor(Math.random() * counter);

      // Decrease counter by 1
      counter--;

      // And swap the last element with it
      let temp = array[counter];
      array[counter] = array[index];
      array[index] = temp;
    }

    return array;
  }

}

module.exports = WichtelController
