var app = angular.module('app', ["ngTouch", "ngAnimate", "angucomplete-alt"]);

app.controller('MainController', ['$scope', '$http', '$rootScope',
    function MainController($scope, $http, $rootScope) {

        // add a new framework on start - hidden
        $scope.formAddFramework = true;
        // about the app section
        $scope.aboutAPI = false;
        // delete a new framework on start - hidden
        $scope.formDeleteFramework = true;
        // show framework section on start
        $scope.showFrameworksTrue = true;
        // initial number of items shown
        $scope.quantity = 10;
        // whether page is loading
        $scope.loading = true;
        // options for filters
        $scope.showOptionsFramework = false;
        // show initial box view
        $scope.showFrameworkBoxes = true;
        // show table view - hidden
        $scope.showFrameworkTable = false;

        // initialize tooltips
        $('.tooltipped').tooltip({
            delay: 0
        });
        $(".button-collapse").sideNav();
        $('.modal-trigger').leanModal();

        // get initial api listing
        $http.get('http://planlodge.com/techconsume/frameworks/json/v1/')
            .success(function(dataSet) {

                $scope.frameworks = dataSet.data;
                $scope.frameCount = dataSet.data.length;
                $scope.loading = false;
                $('.overlay').fadeOut(1000);
                $('.spinner').fadeOut(2000);

            });

        // toggle filter settings
        $scope.toggleOptions = function() {
            $scope.showOptionsFramework = !$scope.showOptionsFramework;
        };

        // show api list in table
        $scope.showFrameworkTableList = function() {
            $scope.showFrameworkBoxes = false;
            $scope.showFrameworkTable = true;
        };

        $scope.showFrameworkBoxList = function() {
            $scope.showFrameworkBoxes = true;
            $scope.showFrameworkTable = false;
        };




        $scope.remoteUrlRequestFn = function(str) {
            return {
                query: str
            };
        };


        $scope.showFrameworks = function() {

            $scope.showFrameworksTrue = true;
            $scope.formAddFramework = true;
            $scope.formDeleteFramework = true;
            $scope.aboutAPI = false;



            $http.get('http://planlodge.com/techconsume/frameworks/json/v1/')
                .success(function(dataSet) {

                    $scope.frameworks = dataSet.data;
                    $scope.frameCount = dataSet.data.length;

                });

            // $scope.myVar = !$scope.myVar;

        };

        $scope.showForm = function() {

            //$scope.formAddFramework = !$scope.formAddFramework;
            $scope.formAddFramework = false;
            $scope.formDeleteFramework = true;
            $scope.showFrameworksTrue = false;
            $scope.aboutAPI = false;
        };

        $scope.showFormDelete = function() {

            //$scope.formDeleteFramework = !$scope.formDeleteFramework;
            $scope.formAddFramework = true;
            $scope.formDeleteFramework = false;
            $scope.showFrameworksTrue = false;
            $scope.aboutAPI = false;
        };

        $scope.showAboutAPI = function() {

            //$scope.formAddFramework = !$scope.formAddFramework;
            $scope.formAddFramework = true;
            $scope.formDeleteFramework = true;
            $scope.showFrameworksTrue = false;
            $scope.aboutAPI = true;
        };

        $scope.addFramework = function() {

            $scope.loading = true;
            $('.overlay').fadeIn(100);
            $('.spinner').fadeIn(200);

            var frameworkAdd = $scope.user.framework;
            var languageAdd = $scope.user.language;
            var linkAdd = $scope.user.link;
            var descriptionAdd = $scope.user.description;

            // alert($scope.framework + '' + languageAdd + '' + linkAdd + '' + descriptionAdd );

            var objFramework = $.param({
                framework: frameworkAdd,
                language: languageAdd,
                link: linkAdd,
                description: descriptionAdd
            });

            $http({
                method: 'POST',
                url: 'http://planlodge.com/techconsume/frameworks/json/v1/',
                data: objFramework, // pass in data as strings
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                } // set the headers so angular passing info as form data (not request payload)
            }).success(function(data) {
                console.log(data);

                Materialize.toast("Added " + frameworkAdd, 5000);

                $scope.user.framework = '';
                $scope.user.language = '';
                $scope.user.link = '';
                $scope.user.description = '';

                $scope.loading = false;
                $('.overlay').fadeOut(1000);
                $('.spinner').fadeOut(2000);

                $scope.showFrameworks();
            });



        };


        $scope.deleteFramework = function() {

            $scope.loading = true;
            $('.overlay').fadeIn(100);
            $('.spinner').fadeIn(200);

            var frameworkDelete = $scope.user.frameworkD;

            var objFramework = $.param({
                framework: frameworkDelete
            });

            $http({
                method: 'DELETE',
                url: 'http://planlodge.com/techconsume/frameworks/json/v1/',
                data: objFramework, // pass in data as strings
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                } // set the headers so angular passing info as form data (not request payload)
            }).success(function(data) {
                console.log(data);

                Materialize.toast("Deleted " + frameworkDelete, 5000);
                $scope.user.frameworkD = '';

                $scope.loading = false;
                $('.overlay').fadeOut(1000);
                $('.spinner').fadeOut(2000);


                $scope.showFrameworks();

            });


        };

        /*
        var xsrf = $.param({framework: 'bb22',language:'bb22',link:'bb22',description:'bb22', id:259});
        $http({
          method  : 'PUT',
          url     : 'http://planlodge.com/techconsume/frameworks',
          data    : xsrf,  // pass in data as strings
          headers : { 'Content-Type': 'application/x-www-form-urlencoded' }  // set the headers so angular passing info as form data (not request payload)
        }).success(function(data) {
        	console.log(data);
        });*/


    }
]);
