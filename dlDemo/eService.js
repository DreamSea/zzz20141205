angular.module('myApp.eService', [])
    .service('eService', ['$rootScope', '$log', '$window', function($rootScope, $log, $window) {
        var pub = {},
            delimiter = '"""',
            pointer = 0,
            maxPointer = 0,
            key = 'type',
            typeLookUp = {
                'program': ['rules-set', 'change-rules-set-row'],
                'gateway': ['test']
            };

        pub.eserviceAttempts = 1; //had to expose this to unit test onDoneResponse
        pub.server = '';
        pub.gatewayGUID = null;

        function broadCastNotification(eventType, obj) {
            $rootScope.$emit(eventType, obj);
        }

        function delegate(str) {
            if (str.length === 0) {
                return;
            }

            var obj = angular.fromJson(str),
                eventType = obj[key],
                label = obj.label,
                evt;

            if (obj.hasOwnProperty('serviceCommand')) {
                if (obj.serviceCommand === 'quit') {
                    $log.warn('timedOut');
                } else if (obj.serviceCommand === 'sessionTimeoutAlert') {
                    $log.warn('session timeout approaching.');
                }
                return;
            }

            if (eventType === 'device') {
                evt = _.findKey(typeLookUp, function(values) {
                    return _.indexOf(values, label) > -1;
                });

                eventType = evt || eventType;
            }

            switch (eventType) {
                case 'program':
                    broadCastNotification('programEvent', obj);
                    $log.log('%c' + str, 'color: purple');
                    break;

                case 'event':
                    broadCastNotification('customEvent', obj);
                    $log.log('%c' + str, 'color: orange');
                    break;

                case 'device':
                    broadCastNotification('deviceEvent', obj);
                    $log.log('%c' + str, 'color: blue');
                    break;

                default:
                    broadCastNotification('gatewayEvent', obj);
                    $log.log('%c' + str, 'color: green');
                    break;
            }

            return;
        }

        /*
         * This parses string to remove any unwanted characters (*, newlines etc)
         * and delegates the processed string.
         *
         * @str {string}
         */
        pub.parseAndDelegate = function(str) {
            var s = str.replace(/^\*+|[\r\n]+|\r+|\n+$/g, ''),
                jsonArray = [];

            if (s.length > 0) {
                jsonArray = s.split(delimiter);

                _.each(jsonArray, function(eService) {
                    delegate(eService);
                });
            }
        };

        /*
         * This makes an XMLHttpRequest to open a connection. It uses a unique id, delimiter
         * and gatewayGWID as query parameters.
         *
         */
        pub.setUpConnection = function() {
            var uuid = new Date().getTime(),
                url = pub.server + '/messageRelay/pConnection' + '?uuid=' + uuid + '&app2=' + delimiter + '&key=' + pub.gatewayGUID;

            pointer = 0;
            maxPointer = 0;

            if ($window.XMLHttpRequest) {
                var xhr = new $window.XMLHttpRequest();
                xhr.onreadystatechange = function() {
                    try {
                        if (xhr.readyState === 3) {
                            pub.onProcessRequest(xhr.responseText);
                        }
                        if (xhr.readyState === 4) {
                            pub.onDoneResponse();
                        }
                    } catch (err) {
                        $log.warn(err);
                    }
                };
                xhr.open('GET', url, true);
                xhr.send();
            }
        };

        /**
         * This handles ready state 3.
         *
         * @response {string}
         */
        pub.onProcessRequest = function(response) {
            var res = response.substring(maxPointer);
            if (res.indexOf(delimiter) > -1) {
                maxPointer = response.length;
                pointer = response.length;
                pub.parseAndDelegate(res);
            } else {
                pointer = response.length; //how is this being used?
            }
        };

        /**
         * This is the handler for ready state 4.
         *
         */
        pub.onDoneResponse = function() {
            if (pub.eserviceAttempts <= 5) {
                pub.eserviceAttempts++;
                $log.debug('READY STATE: 4.about to connect again..');

                setTimeout(pub.setUpConnection, 5000);
            } else {
                $log.warn('eService connection error');
            }
        };

        pub.init = function(config, gatewayGUID) {
            pub.server = config.server;
            pub.gatewayGUID = gatewayGUID;
            pub.setUpConnection();
        };

        return pub;

    }]);
