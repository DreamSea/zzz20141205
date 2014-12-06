// app configurations
var config = {
    server: 'https://systest.digitallife.att.com',
    api: '/penguin/api/',
    apiPath: function() {
        return this.server + this.api;
    },
    domain: 'DL'
};


// angulars app
angular.module('myApp', [
    'ngRoute',
    'ngStorage',
    'myApp.eService'
]).
config(['$routeProvider', function($routeProvider) {
        // app routes
        $routeProvider.when('/login', {
            templateUrl: 'partials/login.html',
            controller: 'LoginCtrl as login'
        });

        $routeProvider.when('/devices', {
            templateUrl: 'partials/devices.html',
            controller: 'DevicesCtrl as devices'
        });

        $routeProvider.when('/programs', {
            templateUrl: 'partials/programs.html',
            controller: 'ProgramsCtrl as programs'
        });

        $routeProvider.when('/device-logs', {
            templateUrl: 'partials/device-logs.html',
            controller: 'DeviceLogsCtrl as deviceLogs'
        });

        $routeProvider.otherwise({
            redirectTo: '/login'
        });
    }])
    .controller('HeaderController', ['$rootScope', '$location', function($rootScope, $location) {
        var self = this;

        // navigation list
        this.navigationList = [{
            name: 'Devices',
            path: '/devices'
        }, {
            name: 'Programs',
            path: '/programs'
        }, {
            name: 'Device Logs',
            path: '/device-logs'
        }];

        // show/hide navigation based upon route
        this.checkHeader = function() {
            if ($location.path() !== '/login') {
                self.showHeader = true;
            } else {
                self.showHeader = false;
            }
        };

        // check for current active navigation route
        this.isActive = function(path) {
            return path === $location.path();
        };

        // on route change check to see if header should be shown
        $rootScope.$on('$routeChangeStart', function(next, current) {
            self.checkHeader();
        });

        this.checkHeader();
    }])
    .controller('LoginCtrl', ['$location', 'apiService', 'Auth', function($location, apiService, Auth) {
        var self = this;

        // destroy auth data and session storage
        Auth.destroy();

        // login form submitted
        this.submit = function() {
            var api = 'authtokens',
                data = {
                    userId: self.userId,
                    password: self.password,
                    appKey: self.appKey,
                    domain: config.domain
                };

            // post to api for login
            apiService.post(api, data, true).then(function(data) {
                var authToken,
                    gatewayGUID,
                    requestToken;

                // get authToken, requestToken, gatewayGUID
                if (data && data.content) {
                    authToken = data.content.authToken;
                    requestToken = data.content.requestToken;
                    if (data.content.gateways && data.content.gateways[0] && data.content.gateways[0].id) {
                        gatewayGUID = data.content.gateways[0].id;
                    }

                    if (authToken && requestToken && gatewayGUID && self.appKey) {
                        // set auth data
                        Auth.set(authToken, requestToken, gatewayGUID, self.appKey);

                        // redirect to devices page
                        $location.path('/devices');
                    }
                }
            });
        };

    }])
    .controller('DevicesCtrl', ['$rootScope', '$timeout', 'timeoutService', 'apiService',
        function($rootScope, $timeout, timeoutService, apiService) {
            var self = this;

            // fetch devices data
            this.fetch = function() {
                self.devices = [];

                apiService.get('devices').then(function(data) {
                    var devicesList = [],
                        includeTypes = ['light-control', 'smart-plug', 'door-lock', 'water-shutoff']; // display only toggle devices

                    if (data && data.content) {
                        _.each(data.content, function(device) {
                            // fetch single device data
                            device.fetch = function() {
                                var path = 'devices/' + device.deviceGuid;
                                apiService.get(path).then(function(data) {
                                    if (data && data.content) {
                                        _.extend(device, data.content);
                                        device.isUpdating = false;
                                    }
                                });
                            };
                            // get attribute property
                            device.get = function(property, returnType) {
                                var attribute;

                                returnType = returnType || 'value';

                                if (device[property]) {
                                    return device[property];
                                }

                                attribute = _.find(device.attributes, {
                                    'label': property
                                });

                                if (attribute && attribute[returnType]) {
                                    return attribute[returnType];
                                } else {
                                    return null;
                                }
                            };

                            // set attribute property
                            device.set = function(property, value) {
                                if (_.has(device, property)) {
                                    device[property] = value;
                                } else {
                                    var attribute = _.find(device.attributes, {
                                        'label': property
                                    });

                                    if (attribute) {
                                        attribute.value = value;
                                    }
                                }
                            };

                            // get attribute action label associate with device
                            device.getActionLabel = function() {
                                switch (device.deviceType) {
                                    case 'light-control':
                                    case 'smart-plug':
                                        return 'switch';

                                    case 'door-lock':
                                        return 'lock';

                                    case 'water-shutoff':
                                        return 'valve';

                                    case 'contact-sensor':
                                        return 'contact-state';

                                    case 'thermostat':
                                        return 'thermostat-mode';

                                    case 'water-sensor':
                                        return 'water-state';

                                    case 'garage-door-controller':
                                        return 'garage-door-state';

                                    case 'garage-door-sensor':
                                        return 'contact-state';

                                    case 'camera':
                                        return 'webcam';

                                    case 'panic-device':
                                        return 'arm-state';
                                }
                            };

                            // get current action value
                            device.getCurrentAction = function() {
                                return device.get(device.getActionLabel());
                            };

                            // get toggle action options
                            device.getActionOptions = function() {
                                var dataType = device.get(device.getActionLabel(), 'dataType').replace('enum:', '');
                                return dataType.split(',');
                            };

                            // update device action value
                            device.updateAction = function(action) {
                                var path = 'devices',
                                    property = device.getActionLabel();

                                if (action !== device.getCurrentAction()) {
                                    // set device property to new value
                                    device.set(property, action);
                                    path += '/' + device.deviceGuid + '/' + property + '/' + action;

                                    // start spinner
                                    device.isUpdating = true;

                                    // save new action value to server
                                    apiService.post(path).then(function(data) {
                                        if (data && data.content && data.content !== -1) {
                                            // set timeout to remove spinner in case update doesn't go through
                                            timeoutService.set(data.content, function() {
                                                device.isUpdating = false;
                                            }, function() {
                                                // if failed fetch the single device
                                                device.fetch();
                                                device.isUpdating = false;
                                            });
                                        } else {
                                            // if failed fetch the single device
                                            device.fetch();
                                        }
                                    });
                                }
                            };

                            // only include toggle devices
                            if (_.contains(includeTypes, device.deviceType) && device.getCurrentAction()) {
                                devicesList.push(device);
                            }
                        });

                        // sort device list by name
                        devicesList = _.sortBy(devicesList, 'deviceType');
                        self.devices = devicesList;
                    }
                });
            };

            // on eService event update device and clear timeout
            $rootScope.$on('deviceEvent', function(e, data) {
                if (data && data.dev && data.label && data.value && data.cqid) {
                    var device = _.find(self.devices, {
                        deviceGuid: data.dev
                    });

                    // clear timeout
                    timeoutService.clear(data.cqid);

                    if (device) {
                        $timeout(function() {
                            device.set(data.label, data.value);
                            device.isUpdating = false;
                        });
                    }
                }
            });

            this.fetch();
        }
    ])
    .controller('ProgramsCtrl', ['$rootScope', '$timeout', 'timeoutService', 'apiService',
        function($rootScope, $timeout, timeoutService, apiService) {
            var self = this;

            // fetch programs data
            this.fetch = function() {
                self.programs = [];

                apiService.get('programs').then(function(data) {
                    if (data && data.content) {
                        _.each(data.content, function(program) {
                            // run a program
                            program.run = function() {
                                var path = 'programs/' + program.id + '/run';

                                // start spinner
                                program.isUpdating = true;

                                // call api to run a program
                                apiService.post(path).then(function(data) {
                                    if (data && data.content && data.content !== -1) {
                                        // set timeout to remove spinner in case update doesn't go through
                                        timeoutService.set(data.content, function() {
                                            program.isUpdating = false;
                                        }, function() {
                                            // if failed refetch programs data
                                            self.fetch();
                                            program.isUpdating = false;
                                        });
                                    } else {
                                        // if failed refetch programs data
                                        self.fetch();
                                    }
                                });
                            };

                            // change active state of a program
                            program.toggleActive = function(active) {
                                var path = 'programs/' + program.id + '/';

                                if (active !== program.active) {
                                    path += active ? 'activate' : 'deactivate';
                                    program.active = active;

                                    // start spinner
                                    program.isUpdating = true;

                                    // save new active state to server
                                    apiService.post(path).then(function(data) {
                                        if (data && data.content && data.content !== -1) {
                                            // set timeout to remove spinner in case update doesn't go through
                                            timeoutService.set(data.content, function() {
                                                program.isUpdating = false;
                                            }, function() {
                                                // if failed refetch programs data
                                                self.fetch();
                                                program.isUpdating = false;
                                            });
                                        } else {
                                            // if failed refetch programs data
                                            self.fetch();
                                        }
                                    });
                                }
                            };

                            self.programs.push(program);
                        });
                    }
                });
            };

            // on eService event update programs and clear timeout
            $rootScope.$on('programEvent', function(e, data) {
                if (data.cqid && data.content) {
                    var content = angular.fromJson(data.content);
                    if (content && content.errorCode === 0) {
                        // clear timeout
                        timeoutService.clear(data.cqid, function() {
                            // if failed refetch programs data
                            self.fetch();
                        });
                    } else {
                        // if failed refetch programs data
                        self.fetch();
                    }
                }
            });

            this.fetch();
        }
    ])
    .controller('DeviceLogsCtrl', ['apiService',
        function(apiService) {
            var self = this;
            this.start = 0; // item start position
            this.count = 50; // max item to show per page

            // enabled/disabled navigation controls
            this.nextPage = false;
            this.previousPage = true;

            // fetch device logs data
            this.fetch = function() {
                var path = 'inbox?maxItemCount=' + this.count + '&start=' + this.start;

                // update navigation controls
                if (this.start === 0) {
                    this.nextPage = false;
                } else {
                    this.nextPage = true;
                }

                self.deviceLogs = [];

                // get device logs data
                apiService.get(path).then(function(data) {
                    if (data && data.content) {
                        _.each(data.content, function(log) {
                            self.deviceLogs.push(log);
                        });
                    }
                });
            };

            // go to previous page
            this.previous = function() {
                this.start += this.count;
                this.fetch();
            };

            // go to next page
            this.next = function() {
                if (this.start === 0) {
                    return;
                } else {
                    this.start -= this.count;
                    this.fetch();
                }
            };

            this.fetch();
        }
    ])
    .service('Auth', ['$sessionStorage', 'eService', function($sessionStorage, eService) {
        // fetch data from session storage if exist
        if ($sessionStorage.auth) {
            _.extend(this, $sessionStorage.auth);

            // start eService
            eService.init(config, this.gatewayGUID);
        }

        // set auth data
        this.set = function(authToken, requestToken, gatewayGUID, appKey) {
            var self = this;

            this.authToken = authToken;
            this.requestToken = requestToken;
            this.gatewayGUID = gatewayGUID;
            this.appKey = appKey;

            // save auth data to session storage
            $sessionStorage.auth = {
                authToken: self.authToken,
                requestToken: self.requestToken,
                gatewayGUID: self.gatewayGUID,
                appKey: self.appKey
            };

            // start eService
            eService.init(config, this.gatewayGUID);
        };

        // delete auth data
        this.destroy = function() {
            this.authToken = null;
            this.requestToken = null;
            this.gatewayGUID = null;
            this.appKey = null;
            delete $sessionStorage.auth;
        };
    }])
    .factory('apiService', ['$http', '$q', '$location', 'Auth', function($http, $q, $location, Auth) {
        var pub = {};

        // get api by path
        pub.get = function(path) {
            var d = $q.defer(),
                headers = {},
                url;

            // set headers from auth data
            if (Auth.authToken && Auth.requestToken && Auth.gatewayGUID && Auth.appKey) {
                url = config.apiPath() + Auth.gatewayGUID + '/' + path;
                headers.authToken = Auth.authToken;
                headers.requestToken = Auth.requestToken;
                headers.appKey = Auth.appKey;

                // get data and return a promise
                $http({
                    method: 'GET',
                    url: url,
                    headers: headers,
                }).success(function(data) {
                    d.resolve(data);
                }).error(function(data) {
                    // if failed redirect to login
                    $location.path('/login');
                });
            } else {
                // if failed redirect to login
                $location.path('/login');
            }

            return d.promise;
        };

        // post api by path
        pub.post = function(path, data, isLogin) {
            var d = $q.defer(),
                headers = {},
                url = config.apiPath() + path;

            // if login use query parameters
            if (isLogin) {
                url += '?' + $.param(data);
            } else if (Auth.authToken && Auth.requestToken && Auth.gatewayGUID && Auth.appKey) {
                // set headers from auth data
                url = config.apiPath() + Auth.gatewayGUID + '/' + path;
                headers.authToken = Auth.authToken;
                headers.requestToken = Auth.requestToken;
                headers.appKey = Auth.appKey;
            }

            // post data and return a promise
            $http({
                method: 'POST',
                url: url,
                headers: headers
            }).success(function(data) {
                d.resolve(data);
            }).error(function(data) {
                // if failed redirect to login
                $location.path('/login');
            });

            return d.promise;
        };

        return pub;
    }])
    .factory('timeoutService', ['$rootScope', '$timeout', function($rootScope, $timeout) {
        var pub = {},
            defaultDuration = 30000; // 30 seconds

        // Timeout map
        pub.timeout = {};

        /**
         * Start timeout
         */
        pub.set = function(id, success, fail, duration) {
            duration = duration || defaultDuration;
            pub.timeout[id] = {
                timeout: $timeout(function() {
                    // call fail function if timeout didn't get clear
                    if (fail) {
                        fail();
                    }
                }, duration),
                success: success,
                fail: fail
            };
        };

        /**
         * Clear timeout
         */
        pub.clear = function(id, fail) {
            if (pub.timeout[id]) {
                $timeout.cancel(pub.timeout[id].timeout);

                $timeout(function() {
                    if (pub.timeout[id] && pub.timeout[id].success) {
                        // call success function when timeout is clear
                        pub.timeout[id].success();
                    }
                }).then(function() {
                    delete pub.timeout[id];
                });
            } else {
                if (fail) {
                    // call fail function if timeout id doesn't exist
                    fail();
                }
            }
        };

        return pub;
    }]);
