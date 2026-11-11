# Changelog

<!-- CHANGELOGGER -->

## [v1.4.3] - 2026-11-11

### Bug fix (1 change)

- Fix dependency installation on PHP 8.3


## [v1.4.2] - 2026-11-11

### Security fix (1 change)

- Update PHP and frontend dependencies to fix known security vulnerabilities


## [v1.4.1] - 2026-11-11

### Bug fix (2 changes)

- Fix blank page after inviting a new member to a group
- Confirming a member no longer sends them an invitation email


## [v1.4.0] - 2026-11-05

### Feature change (1 change)

- Approving/Declining invitation is refactored to use POST requests on a dedicated site. Not via GET URI in your Mail client. This prevents link checker to confirm your invitation accidentally.

### New feature (1 change)

- Group admins can now share a public join link. Participants can register themselves with name, email, and wishlist. Email confirmation is required before joining the group.


## [v1.3.0] - 2026-01-22

### Feature change (4 changes)

- Admin-Dashboard: Group names are unique colored for better understanding.
- New favicon
- Redesign of groups site to use glassmorphism design.
- Landing Page Redesign: Modern winter theme with animated snowflakes and elf hat on logo

### New feature (4 changes)

- Admin-Dashboard: Open Wichtelgroups using user links.
- Group admin can resend email invitations.
- Group admin can change invitation status of users.


## [v1.2.0] - 2026-01-20

### New feature (3 changes)

- New Admin-Dashboard /admin with Basic Auth
- Add statistics history graph and total counts to admin dashboard

### Bug fix (4 changes)

- Use utf8mb4 as DB collaction. Allows emojis
- Fix User::wishlist() returning status instead of wishlist
- Check that wichtel date is after or equal to today

### Other (6 changes)

- Add unit tests for User and Group models
- Upgrade to Laravel 12 & phpunit 12
- Update required php to php8.3
- Endpoints documented with OpenAPI.
- Update test infrastructure for Laravel 12 with SQLite in-memory database
- Drop Webpack; Use Vite, Vue3
- Drop Bulma; Use Tailwind
- Refactor seeder to use Laravel 9 class-based factories

## [1.1.0] - 2020-03-22

### Feature change (1 change)

- Created groups are counted per month.
- Count started groups per month.

### Other (1 change)

- Update to Laravel 6.8

### New feature (4 changes)

- Delete old groups: `wichtel:delete-groups`
- New command `wichtel:delete-users` to delete user records without any groups.
- Add Command `wichtel:inform-deletion`. Inform group admin about upcoming data deletion after successful wichtel action.

## [1.0.0] - 2018-11-25

🤩

## [0.2.0] - 2016-12-27

Switch completely to Laravel Framework.

### Changes

* No `wichteln/` endpoint anymore. To start update the group status to `started`.
* Nice E-Mails with blade templates
* Using a queue to dispatch raffle job and sending mails
* Add wish list of buddy to email

## [0.1.0] - 2016-12-17

The service is only and running at [wichtel.me](https://wichtel.me)

### Features

* Endpoints
  * `wichtelgroup/`: CRUD Endpoint for groups
  * `wichtelgroup/:group_id/wichtelmember`: CRUD Endpoint for members
  * `wichteln/`: Start Secret Santa for a group
* Authentication is set with API tokens per group
* Mails will be sent to members, when
  * added to approve their participation
  * their wichtel buddies are drawn
