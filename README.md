# GQuery
**A simple GraphQL Query Builder.**

*Current Release Version: 0.1.0-alpha.  See Changelog below for notes.*

GQuery is a query builder for [GraphQL](https://graphql.org/).  It's designed to let you send data in as you need to, and then grab a constructed GraphQL query to send to whatever endpoint you desire.  Adaptable inputs let you write queries in several different structures, whichever way fits your needs.

## Installation

The suggested installation method is via composer:

```
composer install aquackenbos/gquery
```

Alternatively, download a release package and place into your autoloaded library directory as needed.

## Changelog

v0.1.0-alpha: Initial release; not yet feature complete.  No (proper) support for Mutations yet.  Enums and Variables are currently both handled via the `GQuery::raw` call, which will be deprecated in a future release.

## Basic Usage

A basic understanding of how GraphQL queries are structured and called is assumed throughout this documentation.  For more information, see [GraphQL's Learn page](https://graphql.github.io/learn/queries/).

### Queries

GQuery utilizes a static factory to create `GQuery\Query` objects that can render into full queries.  The query needs an identifier to call it.  Once you have the object, you can pass it fields to select as part of the query.  You can also set variables you'll want to use in the query.

```
$query = GQuery::query('MyQuery');

$query->selections([
  'hero' => [
    'name',
    'height',
    'friends' => [
      'name'
    ]
  ]
]);
```

```
$query = GQuery::query('MyQueryWithVariables');

$query->variables([
  'heroId' => 'Int'
]);

$query->selection('hero',[
  'name',
  'height'
],[
  'id' => GQuery::raw('$heroId')
]);
```
Once you have set everything you want for the query, you can render it out into a regular text GraphQL Query:
```
$query->render($variables);
```
