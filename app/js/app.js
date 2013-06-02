'use strict';


// Declare app level module which depends on filters, and services
angular.module('rN_assassins', ['rN_assassins.filters', 'rN_assassins.services', 'rN_assassins.directives', 'rN_assassins.controllers']).
  config(['$routeProvider', function($routeProvider) {
    $routeProvider.when('/games', {templateUrl: 'partials/gamesList.html', controller: 'GamesList'});
    $routeProvider.when('/games/:gameId', {templateUrl: 'partials/game.html', controller: 'GameView'});
    $routeProvider.otherwise({redirectTo: '/games'});
  }]);
