'use strict';

/* Controllers */

angular.module('rN_assassins.controllers', []).
  controller('GameView', ['$scope', '$routeParams', '$http', function($scope, $routeParams, $http) {
  	$http.get('data/game_'+$routeParams.gameId+'.json').success(function (data) {
  		$scope.game = data;
  	});
  }]).
  controller('GamesList', ['$scope', '$http', function($scope, $http) {
  	$http.get('data/games.json').success(function (data) {
  		$scope.games = data;
  	});

  }]);