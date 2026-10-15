# HoopTracker

HoopTracker is an NBA stats web app built with PHP and MySQL. You can browse teams, players, games, conferences and MVP picks, create an account, post team-related images, like other users' posts, and run saved or custom SQL queries against the database.

## Features

- **Browse data:** Team, Player, Conference, Game, MVP Pick, User, Favorite Team, Favorite Player and Media pages, each reading live from the database
- **Accounts:** sign up and log in, with passwords stored as bcrypt hashes (`password_hash` / `password_verify`) and PHP sessions
- **Media posts:** upload an image URL tied to a team (validated, CSRF-protected), view your own posts on a dashboard, and like posts from the community feed
- **Saved queries:** five prepared queries on the home page, including joins, a subquery, grouping and aggregates
- **Ad hoc query tool:** a form for running custom `SELECT`, `INSERT`, `UPDATE` and `DELETE` statements

## Tech stack

PHP 8, MySQL / MariaDB (`mysqli`), HTML and CSS

## Database

The schema has 9 tables: `User`, `Team`, `Player`, `Game`, `Conference`, `MVP_Pick`, `Fav_Team`, `Fav_Player` and `Media`. Foreign keys link players to teams, games to home and away teams, and media and favorites to users. `schema.sql` creates everything and loads sample data.

## Run it locally

1. Install [XAMPP](https://www.apachefriends.org) and start **MySQL** in the XAMPP Control Panel.
2. Open http://localhost/phpmyadmin, go to the **Import** tab, and import `schema.sql`. This creates the `hooptracker` database with sample data.
3. In this project folder, run:
```
   php -S localhost:8000
```
4. Open http://localhost:8000/source.html

Sample login: username `DwighKool`, password `password123`

## Configuration

`db.php` defaults to the XAMPP settings (host `127.0.0.1`, user `root`, no password, database `hooptracker`). To use different settings, set the environment variables `DB_HOST`, `DB_USER`, `DB_PASS` and `DB_NAME`. Never commit real credentials.

## Project structure

| File | Purpose |
| --- | --- |
| `source.html` | Home page with navigation, saved queries and the ad hoc query form |
| `db.php` | Shared database connection |
| `schema.sql` | Table definitions and sample data |
| `login.php`, `signup.php`, `logout.php` | Authentication |
| `dashboard.php`, `upload_media.php`, `view_media.php` | Media posting, your posts, and the community feed |
| `Team.php`, `Player.php`, `Game.php`, `Conference.php`, `MVP_Pick.php`, `User.php`, `FavTeam.php`, `FavPlayer.php`, `Media.php` | Table viewers |
| `query.php`, `adhoc_query.php` | Saved and custom queries |

## Notes

- `adhoc_query.php` runs raw SQL from a form. It is meant for local use, so don't deploy it publicly.
- The team and player rows in `schema.sql` are sample data, and the logos and photos are placeholder images.

## Screenshots

[Add a screenshot of the home page and the team table here.]

## Authors

[Your name and any teammates]
