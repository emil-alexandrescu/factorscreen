
var stockapp = angular.module('stockdataApp', ['ui.bootstrap', 'datatables', 'datatables.bootstrap', 'datatables.colvis', 'datatables.tabletools', 'ui-rangeSlider', 'checklist-model', 'ngTable'],
    function($interpolateProvider) {
        $interpolateProvider.startSymbol('<%');
        $interpolateProvider.endSymbol('%>');
    });

stockapp.controller('stockdataController', ['$scope', '$http', '$q', 'ngTableParams', '$modal', '$filter', '$window',
    function stockdataController($scope, $http, $q, ngTableParams, $modal, $filter, $window) {

        // Load data list
        $http.get('/stockdata/list')
        .success(function(data) {
            $scope.stockdata = data;
            $scope.currentData = _.find(data, {id: stockID + ''});
        });

        $scope.chooseStock = function() {
            if ($scope.currentData.id !== stockID) {
                stockID = $scope.currentData.id;
                loadData();
            }
        };

        // load filter list
        $scope.loadFilters = function() {
            return $http.get('/filters')
                .success(function(data) {
                    $scope.filterList = data;
                });
        };
        $scope.loadFilters();

        $scope.changeFilter = function() {
            if ($scope.currentFilter) {
                // $scope.filterOptions = JSON.parse($scope.currentFilter.filterOptions);
                $scope.filterUI = JSON.parse($scope.currentFilter.filterUI);
                $scope.distribution = $scope.currentFilter.distribution;
            }
        };

        $scope.refreshFilter = function() {
            $scope.currentFilter = null;
        }

        $scope.createFilter = function() {
            $http.post('/filters', {
                // filterOptions: JSON.stringify($scope.filterOptions),
                filterUI: JSON.stringify($scope.filterUI),
                distribution: $scope.distribution,
                title: $scope.newFilterTitle
            }).success(function(data){
                $scope.filterList.push(data);
                $scope.currentFilter = data;
                $scope.newFilterTitle = '';
                $scope.changeFilter();
            });
        };

        $scope.updateFilter = function() {
            $http.put('/filters/' + $scope.currentFilter.id, {
                // filterOptions: JSON.stringify($scope.filterOptions),
                filterUI: JSON.stringify($scope.filterUI),
                distribution: $scope.distribution,
                title: $scope.currentFilter.title
            }).success(function(data) {
                var index = _.findIndex($scope.filterList, {id: $scope.currentFilter.id});
                $scope.filterList[index] = data;
                $scope.currentFilter = data;
            });
        };

        $scope.deleteFilter = function() {
            $http.delete('/filters/' + $scope.currentFilter.id)
            .success(function(data){
                var index = _.findIndex($scope.filterList, {id: $scope.currentFilter.id});
                $scope.filterList.splice(index, 1);
                $scope.newFilterTitle = '';
                $scope.currentFilter = null;
            });
        }

        $scope.data = [];
        $scope.distribution = 'value';
        $scope.new_criteria = '';
        $scope.filterOptions = {};
        $scope.filterAttributes = {
            'market_cap': { field: 'market_cap', title: 'Market cap', is_weight: false, step: 1, decimal: 0},
            'z_score': { field: 'z_score', title: 'Z-score', is_weight: true, step: 0.01, decimal: 5},
            'm_score': { field: 'm_score', title: 'M-score', is_weight: true, step: 0.01, decimal: 5},
            'f_score': { field: 'f_score', title: 'F-score', is_weight: true, step: 0.01, decimal: 5},
            'pe_ratio': { field: 'pe_ratio', title: 'PE-ratio', is_weight: true, step: 0.01, decimal: 5},
            'forward_pe': { field: 'forward_pe', title: 'Forward PE', is_weight: true, step: 0.01, decimal: 5},
            'forward_growth': { field: 'forward_growth', title: 'Forward growth', is_weight: true, step: 0.01, decimal: 5},
            'pb_ratio': { field: 'pb_ratio', title: 'PB-ratio', is_weight: true, step: 0.01, decimal: 5},
            'asset_growth': { field: 'asset_growth', title: 'Asset growth', is_weight: true, step: 0.01, decimal: 5},
            'ret_1y': { field: 'ret_1y', title: 'Ret 1-year', is_weight: true, step: 0.01, decimal: 5},
            'off_max_15': { field: 'off_max_15', title: 'Off max 15', is_weight: true, step: 0.01, decimal: 5},
            'roe': { field: 'roe', title: 'ROE', is_weight: true, step: 0.01, decimal: 5},
            'basic_nri_pct_diff': { field: 'basic_nri_pct_diff', title: 'Basic NRI-PCT diff', is_weight: true, step: 0.01, decimal: 5},
            'eps_rsd': { field: 'eps_rsd', title: 'EPS RDS', is_weight: true, step: 0.01, decimal: 5},
            'eps_gr': { field: 'eps_gr', title: 'EPS GR', is_weight: true, step: 0.01, decimal: 5},
            'ss_demand': { field: 'ss_demand', title: 'SS demand', is_weight: true, step: 0.01, decimal: 5},
            'ss_util': { field: 'ss_util', title: 'SS util', is_weight: true, step: 0.01, decimal: 5},
            'accruals': { field: 'accruals', title: 'Accruals', is_weight: true, step: 0.01, decimal: 5},
            'roa': { field: 'roa', title: 'ROA', is_weight: true, step: 0.01, decimal: 5},
            'issuance': { field: 'issuance', title: 'Issuance', is_weight: true, step: 0.01, decimal: 5},
            'noa': { field: 'noa', title: 'NOA', is_weight: true, step: 0.01, decimal: 5},
            'profitability': { field: 'profitability', title: 'Profitability', is_weight: true, step: 0.01, decimal: 5},
            'beta': { field: 'beta', title: 'Beta', is_weight: true, step: 0.01, decimal: 5}
        };
        $scope.filterAttributesToShow = _.filter($scope.filterAttributes, function(field) { return field.field != "market_cap"; });
        $scope.filterUI = {
            country: [],
            industry: [],
            exchange: [],
            advanced: []
        };
        $scope.filter = {
            country: [],
            industry: [],
            exchange: [],
            advanced: []
        };
        $scope.showApplyFilter = false;
        $scope.isLoading = false;
        $scope.dynamicColumnsVisibility = [];

        $scope.$watch('distribution', function(nv) {
            if (nv && $scope.allData) {
                $scope.data = $scope.allData[$scope.distribution];
                filterData();
            }
        });

        function makeRank(data, rankField) {
            var result = {};
            var fields = _.pluck($scope.filterAttributesToShow, 'field');
            _.each(data, function(row) {
                result[row.id] = _.clone(row);
            });
            var groupedTmp = _.groupBy(data, rankField);

            _.each(fields, function(field) {
                var tmp = _.map(groupedTmp, function(insideArr){
                    return _.sortBy(insideArr, field)
                });

                _.each(tmp, function(tmp2) {
                    var count = tmp2.length;
                    _.each(tmp2, function(row, index) {
                        result[row.id][field] = (1 / count * index).toFixed(2) * 1;
                    });
                });
            });
            return _.chain(result)
                .map(function(r){
                    return r;
                })
                .sortBy('id')
                .value();
        }

        var init = function() {

            $scope.tableParams = new ngTableParams({
                page: 1,
                count: tableRows,
                sortable: true
            }, {
                total: function() { return $scope.filteredData.length; },
                getData: function(params) {
                    params.total($scope.filteredData.length);

                    if(!$scope.filteredData || !$scope.filteredData.length) return [];

                    var data = _.map($scope.filteredData, function(row) {
                        row.weight = $scope.getAvgWeight(row);
                        row.userWeight = $scope.getUserAvgWeight(row);
                        return row;
                    });

                    var orderedData = params.sorting() ? $filter('orderBy')(data, params.orderBy()) : data;

                    var slicedData = orderedData.slice((params.page() - 1) * params.count(), params.page() * params.count());

                    _.each($scope.filterAttributesToShow, function(field, index) {
                        $scope.dynamicColumnsVisibility[index] = $scope.isMetricsFieldVisible(field.field);
                    });

                    if(params.count() != tableRows) {
                        tableRows = params.count();
                        $http.post('/preferences/STOCKDATA_DETAIL_TABLE_ROWS', {value: tableRows});
                    }
                    return slicedData;
                }
            });

            loadData();
        };

        var loadData = function() {
            $http.get('/stockdata/' + stockID + '/json').
                success(function(data, status, headers, config) {
                    $scope.allData = {
                        value: data,
                        'rank-country': makeRank(data, 'country'),
                        'rank-industry': makeRank(data, 'industry')
                    };
                    $scope.data = $scope.allData[$scope.distribution];
                    filterData();
                }).
                error(function(data, status, headers, config) {
                    $scope.qLoading.resolve(true);
                });
        };
        $scope.isMetricsFieldVisible = function(field) {
            var obj = _.findWhere($scope.filterUI.advanced, {field: field});

            if(typeof obj === 'undefined') return false;
            return true;
        };
        var reloadFilterOptions = function() {
            $scope.filterOptions.countrys = _.chain(getFilterData("country")).pluck('country').uniq().filter(isSet).sort().value();
            $scope.filterOptions.exchanges = _.chain(getFilterData("exchange")).pluck('exchange').uniq().filter(isSet).sort().value();
            $scope.filterOptions.industries = _.chain(getFilterData("industry")).pluck('industry').uniq().filter(isSet).sort().value();

            _.each($scope.filterAttributes, function(attr) {
                // var tmp = _.chain(getFilterData(null)).pluck(attr.field).value();
                var tmp = _.chain($scope.data).pluck(attr.field).value();
                if(typeof $scope.filterOptions[attr.field] === "undefined") $scope.filterOptions[attr.field] = {};
                $scope.filterOptions[attr.field].min = _.min(tmp);
                $scope.filterOptions[attr.field].max = _.max(tmp);
                $scope.filterOptions[attr.field].values = tmp;

                // check current value range
                var curFilterIndex = _.findIndex($scope.filterUI.advanced, {field: attr.field});
                if($scope.filter.advanced.length > 0 && curFilterIndex !== -1 ) {
                    if($scope.filterUI.advanced[curFilterIndex].min < $scope.filterOptions[attr.field].min) {
                        $scope.filter.advanced[curFilterIndex].min = $scope.filterOptions[attr.field].min;
                        $scope.filterUI.advanced[curFilterIndex].min = $scope.filterOptions[attr.field].min;
                    }
                    if($scope.filterUI.advanced[curFilterIndex].max > $scope.filterOptions[attr.field].max) {
                        $scope.filter.advanced[curFilterIndex].max = $scope.filterOptions[attr.field].max;
                        $scope.filterUI.advanced[curFilterIndex].max = $scope.filterOptions[attr.field].max;
                    }
                }
            });
        };

        var isSet = function(val) {
            return val !== "" && val !== null && typeof val !== "undefined";
        };
        var getFilterData = function(exclude) {
            return _.chain($scope.data)
                    .filter(function(row) {
                        if( exclude != "country" && $scope.filter.country.length > 0 && $scope.filter.country.indexOf(row.country) === -1 ) return false;
                        if( exclude != "exchange" && $scope.filter.exchange.length > 0 && $scope.filter.exchange.indexOf(row.exchange) === -1) return false;
                        if( exclude != "industry" && $scope.filter.industry.length > 0 && $scope.filter.industry.indexOf(row.industry) === -1) return false;

                        for(var i = 0; i < $scope.filter.advanced.length; i++) {
                            if(exclude == $scope.filter.advanced[i].field) continue;
                            if($scope.filter.advanced[i].min > row[$scope.filter.advanced[i].field]) return false;
                            if($scope.filter.advanced[i].max < row[$scope.filter.advanced[i].field]) return false;
                        }

                        return true;
                    })
                    .sortBy(function(row) { return $scope.getAvgWeight(row); })
                    .value();
        }

        var filterData = function() {

            $scope.isLoading = true;

            reloadFilterOptions();
            $scope.filteredData = getFilterData();

            $scope.tableParams.reload();

            $scope.isLoading = false;

        };

        $scope.getAvgWeight = function(row) {
            if(!row) return 0;
            var sum = 0;
            var sum_weight = 0;
            _.each($scope.filterAttributes, function(attr, field) {
                if(!attr.is_weight) return;
                var filter = _.findWhere($scope.filter.advanced, {field: field});

                var weight = 1;
                var val = parseFloat(row[field]);
                sum += isNaN(val) ? 0 : val * weight;
                sum_weight += weight;
            });

            if(sum_weight == 0) return 0;

            return Math.round(sum/sum_weight * 100) / 100;
        };
        $scope.getUserAvgWeight = function(row) {
            if(!row) return 0;
            var sum = 0;
            var sum_weight = 0;
            _.each($scope.filterAttributes, function(attr, field) {
                if(!attr.is_weight) return;
                var filter = _.findWhere($scope.filter.advanced, {field: field});

                if(_.isUndefined(filter)) {

                }
                else {
                    var weight =  filter.weight
                    var val = parseFloat(row[field]);
                    sum += isNaN(val) ? 0 : val * weight;
                    sum_weight += weight;
                }
            });

            if(sum_weight == 0) return 0;

            return Math.round(sum/sum_weight * 100) / 100;
        };

        $scope.getRemainingCriterias = function() {
            return _.chain($scope.filterAttributes).filter(function(attr, field) {
                var filter = _.findWhere($scope.filterUI.advanced, {field: field});
                if(typeof filter !== "undefined") return false;
                return true;
            }).value();
        };

        $scope.onAddNewCriteria = function() {
            $scope.remainingCriterias = $scope.getRemainingCriterias();
            if($scope.remainingCriterias.length == 0) return;
            $scope.new_criteria = $scope.remainingCriterias[0].field;
        };
        $scope.onReset = function() {
            $scope.filterUI.advanced = [];
            $scope.onApplyFilter();
        };
        $scope.onConfirmNewCriteria = function() {
            $scope.filterUI.advanced.push({
                field: $scope.new_criteria,
                min: $scope.filterOptions[$scope.new_criteria].min,
                max: $scope.filterOptions[$scope.new_criteria].max,
                weight: 1
            });

            $scope.new_criteria = '';
        };
        $scope.onCancelNewCriteria = function() {
            $scope.new_criteria = '';
        };
        $scope.onRemoveCriteria = function(index) {
            $scope.filterUI.advanced.splice(index, 1);
        };
        $scope.onApplyFilter = function() {
            $scope.filter = JSON.parse(JSON.stringify($scope.filterUI));
            $scope.showApplyFilter = false;
        };
        $scope.showDetail= function(row) {
            var modalInstance = $modal.open({
                animation: true,
                templateUrl: 'detail.html',
                controller: 'detailModalController',
                size: 'lg',
                resolve: {
                    item: function () {
                        return row;
                    },
                    filterAttributes: function() {
                        return $scope.filterAttributes;
                    },
                    calcWeight: function() {
                        return $scope.getAvgWeight;
                    }
                }
            });
        };

        $scope.$watch('filter', filterData, true);
        $scope.$watch('filterUI.country', function() {
            $scope.onApplyFilter();
        }, true);
        $scope.$watch('filterUI.exchange', function() {
            $scope.onApplyFilter();
        }, true);
        $scope.$watch('filterUI.industry', function() {
            $scope.onApplyFilter();
        }, true);
        $scope.$watch('filterUI.advanced', function() {
            $scope.showApplyFilter = true;
        }, true);

        init();
    }])
    .controller('detailModalController', ['$scope', 'item', 'filterAttributes', 'calcWeight', function($scope, item, filterAttributes, calcWeight) {
        $scope.item = item;
        $scope.filterAttributes = _.chain(filterAttributes).filter(function(attr, field) {
                return true;
            }).value();
        $scope.calcWeight= calcWeight;
    }]);


stockapp.directive('chartSlider', function($document) {
    var link = function($scope, $elem, $attr) {

        var updateChart = function() {
            if(!$scope.data || $scope.data == null || $scope.data.length < 2) return;
            var width = $scope.width;
            var height = $scope.height;
            var min_height = height / 10;
            if(width == 0) width = $elem.width();
            if(height == 0) height = $elem.height();
            var count = Math.floor(width / 2);
            var interval = ($scope.max - $scope.min)/count;
            var default_data = {};
            for(var k = 0; k <= count; k++) default_data[k] = 0;
            var data = _.chain($scope.data).groupBy(function(val) {
                return Math.floor((val - $scope.min)/interval);
            }).sort().value();

            data = _.chain(default_data).extend(data).map(function(val, x) { return {x:parseFloat(x), y: val.length}; }).value();

            var yMin = _.min(data, function(d) { return d.y; }).y;
            var yMax = _.max(data, function(d) { return d.y; }).y;
            var yInterval = yMax - yMin == 0 ? 1 : (height-min_height) / (yMax - yMin);

            d3.select($elem[0]).selectAll("svg").remove();

            var xFunc = function(x) { var xRet = x * 2; if(xRet < 0 || isNaN(xRet)) return 0; else return xRet; };
            var yFunc = function(y) { var yRet = y * yInterval; if(yRet < 0 || isNaN(yRet)) return 0; else return yRet; };

            var svg = d3.select($elem[0])
                .append('svg')
                .attr('class', 'chart bar')
                .attr('width', width)
                .attr('height', height)
                .append('g');

            var x = d3.scale.linear().range([0, width]);
            var y = d3.scale.linear().range([0, height]);

            //x axis
            svg.append("line")
                .attr("x1", 0)
                .attr("y1", height)
                .attr("x2", width)
                .attr("y2", height)
                .attr("stroke", "steelblue");
            if($scope.min < 0) {
                svg.append("line")
                    .attr("x1", Math.floor((0 - $scope.min)/interval))
                    .attr("y1", 0)
                    .attr("x2", Math.floor((0 - $scope.min)/interval))
                    .attr("y2", height)
                    .attr("stroke", "maroon");
            }

            svg.selectAll("bar")
                .data(data)
                .enter().append("rect")
                .style("fill", "steelblue")
                .attr("x", function(d) { return xFunc(d.x); })
                .attr("width", 1)
                .attr("y", function(d) { return height - min_height - yFunc(d.y); })
                .attr("height", function(d) { return yFunc(d.y) + min_height; });
        };

        var updateScrollHandler = function() {
            var width = $scope.width;
            var height = $scope.height;
            var min_height = height / 10;
            if(width == 0) width = $elem.width();
            if(height == 0) height = $elem.height();
            var count = Math.floor(width / 2);
            var interval = ($scope.max - $scope.min)/count;

            $divMin = $elem.find('.chart-slider-min-handler');
            $divMax = $elem.find('.chart-slider-max-handler');
            if($divMin.length == 0) {
                $divMin = angular.element('<div class="chart-slider-handler chart-slider-min-handler"><div class="chart-slider-handler-ruler"></div></div>');
                $divMax = angular.element('<div class="chart-slider-handler chart-slider-max-handler"><div class="chart-slider-handler-ruler"></div></div>');
                $elem.css('position', 'relative');
                $elem.append($divMin);
                $elem.append($divMax);

                //drag event

                var handlers = $elem.find('.chart-slider-handler');
                handlers.on('mousedown touchstart', function($evt) {
                    if($elem.find(".chart-slider-dragging").length > 0) return;

                    var target = angular.element(this);
                    target.addClass('chart-slider-dragging');

                    var roundVal = function(val) {
                        var k = 1;
                        for(var i = 0; i < $scope.decimal; i++) k = k * 10;
                        return Math.round(val * k) / k;
                    };
                    var slider_process = function($evt) {
                        var x;
                        if ($evt.pageX) {
                            x = $evt.pageX - $elem.offset().left;
                        }else {
                            x = $evt.originalEvent.changedTouches[0].pageX - $elem.offset().left;
                        }

                        x = roundVal(x / 2 * interval + $scope.min);

                        if(x < $scope.min) x = $scope.min;
                        if(x > $scope.max) x = $scope.max;

                        if(target.hasClass('chart-slider-min-handler') && x > $scope.modelMax) x = $scope.modelMax;
                        if(target.hasClass('chart-slider-max-handler') && x < $scope.modelMin) x = $scope.modelMin;

                        $scope.$apply(function() {
                            if(target.hasClass('chart-slider-min-handler')) {
                                $scope.modelMin = x;
                            }
                            else {
                                $scope.modelMax = x;
                            }
                        });


                        updateScrollHandler();
                    };
                    var onMouseMove = function($evt) {
                        slider_process($evt);
                    };

                    var onMouseUp = function($evt) {
                        $document.off("mousemove touchmove", onMouseMove);
                        $document.off("mouseup touchcancel", onMouseUp);
                        target.removeClass('chart-slider-dragging');
                    }
                    $document.on("mousemove touchmove", onMouseMove);
                    $document.on('mouseup touchend', onMouseUp);
                });
            }

            $divMin.css('left', (($scope.modelMin - $scope.min)/interval * 2 - $divMin.width() / 2) + 'px');
            $divMax.css('left', (($scope.modelMax - $scope.min)/interval * 2 - $divMax.width() / 2) + 'px');
        };


        // _.each(['min', 'max', 'modelMax', 'modelMin'], function(param) {

        //     console.log($scope.min + '~' + $scope.max + ',' + $scope.modelMin + '~' + $scope.modelMax);

        //     $scope.$watch(param, updateScrollHandler, true);
        // });

        _.each(['min', 'max', 'data', 'modelMax', 'modelMin'], function(param) {
            $scope.$watch(param, function() {
                // $scope.modelMin = $scope.modelMin;
                // $scope.modelMax = $scope.modelMax;
                updateChart();
                updateScrollHandler();
            }, true);
        });
    };

    return {
        restrict: 'E',
        scope: {
            modelMin: '=modelMin',
            modelMax: '=modelMax',
            data: '=data',
            min: '=min',
            max: '=max',
            decimal: '@',
            width: '@',
            height: '@'
        },
        link: link
    };
})
.directive('ngMultiselect', function() {
    function link($scope, $elem, $attr) {
        $scope.show = false;
        $scope.label = $scope.ngMultiselect + '(All)';

        $scope.onSelect= function() {
            $scope.show = !$scope.show;
        };
        $scope.close = function() {
            $scope.show = false;
        };

        $scope.$watch('model', function() {
            $scope.label = $scope.ngMultiselect + '(' + ($scope.model.length == 0 || $scope.model.length == $scope.options.length ? 'All' : $scope.model.length) + ')';
        }, true);


        $scope.checkAll = function () {
            $scope.model.length = 0;
            _.each($scope.options, function(itm) {
                $scope.model.push(itm);
            });
        };
        $scope.uncheckAll = function () {
            $scope.model.length = 0;
        };
    };

    return {
        restrict: 'A',
        replace: true,
        template: '<div class="ng-multiselect-container" style="position:relative;color:#000;" click-anywhere-but-here="close()" is-active="show"><div style="cursor:pointer;width:100%;border-radius:3px;height:100%;line-height:100%;text-align:center;border:1px solid #aaa;min-height:25px;line-height:25px;background:#fff" ng-click="onSelect()"><%label%><i class="fa fa-caret-down pull-right" style="color:#000;margin-right:8px;margin-top:5px;"></i></div><div style="position:absolute;left:0px;width:100%;background:#fff;margin-top:-1px;border:1px solid #aaa;height:160px;overflow-y:scroll;z-index:100;" ng-show="show"><div style="padding:2px;"><button class="btn btn-default" ng-click="checkAll()" style="padding:2px;">Check all</button><button style="padding:2px;" class="btn btn-default" ng-click="uncheckAll()">Uncheck all</button><div ng-repeat="option in options" style="cursor:pointer;height:20px;" class="item"><span style="padding-top:2px;"><input type="checkbox" checklist-model="model" checklist-value="option" style="width:30px;margin-top:3px;" class="pull-left" /></span> <span class="" style="height:100%;line-height:20px;"><%option%></span></div></div></div>',
        scope: {
            ngMultiselect: '@',
            options: '=options',
            model: '=model'
        },
        link: link
    };
})
.directive('clickAnywhereButHere', ['$document', function ($document) {
    return {
        link: function postLink(scope, element, attrs) {
            var onClick = function (event) {
                var isChild = $(element).has(event.target).length > 0;
                var isSelf = element[0] == event.target;
                var isInside = isChild || isSelf;
                if (!isInside) {
                    scope.$apply(attrs.clickAnywhereButHere)
                }
            }
            scope.$watch(attrs.isActive, function(newValue, oldValue) {
                if (newValue !== oldValue && newValue == true) {
                    $document.bind('click', onClick);
                }
                else if (newValue !== oldValue && newValue == false) {
                    $document.unbind('click', onClick);
                }
            });
        }
    };
}]);;
