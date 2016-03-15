@extends('layout.layout')

@section('content')
<h3><i class="fa fa-dashboard"></i> Stock Data</h3>
<script>
var stockID = {{$stockdata->id}};
var tableRows = {{ $tableRowsCount == null ? 25 : $tableRowsCount }};
</script>
<link rel="stylesheet" href=" {{ asset('/bower_components/ng-table/dist/ng-table.min.css') }}">
<script src="{{ asset('/bower_components/ng-table/dist/ng-table.min.js') }}"></script>
<script src="{{ asset('/js/lib/stockdata.js') }}"></script>
<div class="content-panel" ng-app="stockdataApp" ng-controller="stockdataController">
    <center>
    <div class="row">
        <div class="col-md-6">
            <div class="white-panel search-panel pn">
                <div class="white-header" style="margin-bottom:0px;">
                    <h5>Data File</h5>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <select class="form-control" ng-model="currentData" ng-options="data as data.title for data in stockdata" ng-change="chooseStock()">
                        </select>
                    </div>
                </div>
            </div>
            <div class="white-panel search-panel pn">
                <div class="white-header" style="margin-bottom:0px;">
                    <h5>Distribution</h5>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <select class="form-control" ng-model="distribution">
                            <option selected value="value">Value</option>
                            <option value="rank-country">Rank-Country</option>
                            <option value="rank-industry">Rank-Industry</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="white-panel search-panel pn">
                <div class="white-header" style="margin-bottom:0px;">
                    <h5>Filter options</h5>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div ng-multiselect="Country" options="filterOptions.countrys" model="filterUI.country"></div>
                    </div>
                    <div class="col-md-3">
                        <div ng-multiselect="Exchange" options="filterOptions.exchanges" model="filterUI.exchange"></div>
                    </div>
                    <div class="col-md-5">
                        <div ng-multiselect="Industry" options="filterOptions.industries" model="filterUI.industry"></div>
                    </div>
                </div>

                <div class="row hidden-xs" ng-if="filterUI.advanced.length > 0" style="border-bottom:1px solid #eee;">
                    <div class="col-sm-2">field</div>
                    <div class="col-sm-2">min</div>
                    <div class="col-sm-4"></div>
                    <div class="col-sm-2">max</div>
                    <div class="col-sm-1">weight</div>
                    <div class="col-sm-1">
                    </div>
                </div>
                <div class="row" ng-repeat="(index, criteria) in filterUI.advanced">
                    <div class="col-sm-2 col-xs-12 mb10 visible-xs"><% filterAttributes[criteria.field].title %> <button class="btn btn-sm btn-danger" ng-click="onRemoveCriteria(index)">
                        <i class="fa fa-trash-o"></i>
                    </button></div>
                    <div class="col-sm-2 col-xs-12 mb10 hidden-xs"><% filterAttributes[criteria.field].title %></div>
                    <div class="col-sm-2 col-xs-2 mb10"><input type="number" ng-model="criteria.min" /></div>
                    <div class="col-sm-4 col-xs-6 mb10">
                        <chart-slider model-min="criteria.min" model-max="criteria.max" data="filterOptions[criteria.field].values" min="filterOptions[criteria.field].min" max="filterOptions[criteria.field].max" decimal="<% filterAttributes[criteria.field].decimal %>" width="200" height="25"></chart-slider>
                    </div>
                    <div class="col-sm-2 col-xs-2 mb10"><input type="number" ng-model="criteria.max" /></div>
                    <div class="col-sm-1 col-xs-2 mb10" ng-if="filterAttributes[criteria.field].is_weight"><input type="number" ng-model="criteria.weight" /></div>
                    <div class="col-sm-1 mb10 hidden-xs">
                        <button class="btn btn-sm btn-danger" ng-click="onRemoveCriteria(index)">
                            <i class="fa fa-trash-o"></i>
                        </button>
                    </div>
                </div>

                <div class="row" ng-show="new_criteria != ''" style="border-top:1px solid #eee;">
                    <div class="col-md-12">
                        <select ng-model="new_criteria" ng-options="c.field as c.title for c in remainingCriterias" style="width:250px;"></select>
                        <button class="btn btn-sm btn-primary" style="margin-left:10px;margin-right:10px;" ng-click="onConfirmNewCriteria()">
                            <i class="fa fa-check-square"></i>
                        </button>
                        <button class="btn btn-sm btn-danger" ng-click="onCancelNewCriteria()">
                            <i class="fa fa-trash-o"></i>
                        </button>
                    </div>
                </div>
                <div class="row" ng-if="new_criteria == ''" style="border-top:1px solid #eee;">
                    <div class="col-md-12">
                        <button class="btn btn-primary pull-left" style="margin-right:10px;" ng-click="onAddNewCriteria()">
                            <i class="fa fa-plus"></i>Add criteria
                        </button>
                        <button class="btn btn-danger pull-left" ng-click="onReset()">
                            <i class="fa fa-times-circle"></i>Reset
                        </button>
                        <button class="btn btn-success pull-right" ng-show="true" ng-click="onApplyFilter()">
                            <i class="fa fa-check-square"></i>Apply
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </center>
    <div class="adv-table" style="margin:10px; overflow-y: auto;">
        <div class="loading" ng-show="isLoading"><div class="loader">Loading...</div></div>
        <table cellpadding="0" cellspacing="0" border="0" class="display table table-bordered" id="hidden-table-info" ng-table="tableParams">
            <!-- <thead>
                <tr>
                    <th>Company Name</th>
                    <th>Symbol</th>
                    <th>Rank</th>
                    <th>Exchange</th>
                    <th>Industry</th>
                    <th>Market cap</th>
                    <th>Weighted Avg Sorted</th>
                    <th ng-repeat="field in filterAttributesToShow" ng-show="isMetricsFieldVisible(field.field)"><%field.title%></th>
                </tr>
            </thead>
            <tbody>
                <tr ng-repeat="row in filteredData" ng-click="showDetail(row)" style="cursor:pointer;">
                    <td><% row.name %></td>
                    <td><% row.symbol %></td>
                    <td><% row.rank %></td>
                    <td><% row.exchange %>(<% row.country %>)</td>
                    <td><% row.industry %></td>
                    <td><% row.market_cap %></td>
                    <td><% getAvgWeight(row) %></td>
                    <th ng-repeat="field in filterAttributesToShow" ng-show="isMetricsFieldVisible(field.field)"><%row[field.field]%></th>
                  <!-- <td ng-repeat="(index, criteria) in filterUI.advanced" ng-if="criteria.field != 'market_cap'"><%row[criteria.field]%></td> -->
                <!--</tr>
            </tbody> -->
            <tr ng-repeat="row in $data" ng-click="showDetail(row)" style="cursor:pointer;">
                <td data-title="'Company Name'" sortable="'name'" ng-bind="row.name"></td>
                <td data-title="'Symbol'" sortable="'symbol'" ng-bind="row.symbol"></td>
                <!-- <td data-title="'Rank'" sortable="'rank'" ng-bind="row.rank"></td> -->
                <td data-title="'Exchange'" sortable="'exchange'" ng-bind="row.exchange"></td>
                <td data-title="'Industry'" sortable="'industry'" ng-bind="row.industry"></td>
                <td data-title="'Market cap'" sortable="'market_cap'" ng-bind="row.market_cap"></td>
                <td data-title="'Equal-Weight All Factor Average'" sortable="'weight'"><% row.weight %></td>
                <td data-title="'User Weighted Average'" sortable="'userWeight'"><% row.userWeight %></td>
                <!-- <td ng-repeat="field in filterAttributesToShow" data-title="field.title" sortable="field.field"><%row[field.field]%></td> -->
                <td data-title="filterAttributesToShow[0].title" sortable="filterAttributesToShow[0].field" ng-if="dynamicColumnsVisibility[0]"><%row[filterAttributesToShow[0].field]%></td>
                <td data-title="filterAttributesToShow[1].title" sortable="filterAttributesToShow[1].field" ng-if="dynamicColumnsVisibility[1]"><%row[filterAttributesToShow[1].field]%></td>
                <td data-title="filterAttributesToShow[2].title" sortable="filterAttributesToShow[2].field" ng-if="dynamicColumnsVisibility[2]"><%row[filterAttributesToShow[2].field]%></td>
                <td data-title="filterAttributesToShow[3].title" sortable="filterAttributesToShow[3].field" ng-if="dynamicColumnsVisibility[3]"><%row[filterAttributesToShow[3].field]%></td>
                <td data-title="filterAttributesToShow[4].title" sortable="filterAttributesToShow[4].field" ng-if="dynamicColumnsVisibility[4]"><%row[filterAttributesToShow[4].field]%></td>
                <td data-title="filterAttributesToShow[5].title" sortable="filterAttributesToShow[5].field" ng-if="dynamicColumnsVisibility[5]"><%row[filterAttributesToShow[5].field]%></td>
                <td data-title="filterAttributesToShow[6].title" sortable="filterAttributesToShow[6].field" ng-if="dynamicColumnsVisibility[6]"><%row[filterAttributesToShow[6].field]%></td>
                <td data-title="filterAttributesToShow[7].title" sortable="filterAttributesToShow[7].field" ng-if="dynamicColumnsVisibility[7]"><%row[filterAttributesToShow[7].field]%></td>
                <td data-title="filterAttributesToShow[8].title" sortable="filterAttributesToShow[8].field" ng-if="dynamicColumnsVisibility[8]"><%row[filterAttributesToShow[8].field]%></td>
                <td data-title="filterAttributesToShow[9].title" sortable="filterAttributesToShow[9].field" ng-if="dynamicColumnsVisibility[9]"><%row[filterAttributesToShow[9].field]%></td>
                <td data-title="filterAttributesToShow[10].title" sortable="filterAttributesToShow[10].field" ng-if="dynamicColumnsVisibility[10]"><%row[filterAttributesToShow[10].field]%></td>
                <td data-title="filterAttributesToShow[11].title" sortable="filterAttributesToShow[11].field" ng-if="dynamicColumnsVisibility[11]"><%row[filterAttributesToShow[11].field]%></td>
                <td data-title="filterAttributesToShow[12].title" sortable="filterAttributesToShow[12].field" ng-if="dynamicColumnsVisibility[12]"><%row[filterAttributesToShow[12].field]%></td>
                <td data-title="filterAttributesToShow[13].title" sortable="filterAttributesToShow[13].field" ng-if="dynamicColumnsVisibility[13]"><%row[filterAttributesToShow[13].field]%></td>
                <td data-title="filterAttributesToShow[14].title" sortable="filterAttributesToShow[14].field" ng-if="dynamicColumnsVisibility[14]"><%row[filterAttributesToShow[14].field]%></td>
                <td data-title="filterAttributesToShow[15].title" sortable="filterAttributesToShow[15].field" ng-if="dynamicColumnsVisibility[15]"><%row[filterAttributesToShow[15].field]%></td>
                <td data-title="filterAttributesToShow[16].title" sortable="filterAttributesToShow[16].field" ng-if="dynamicColumnsVisibility[16]"><%row[filterAttributesToShow[16].field]%></td>
                <td data-title="filterAttributesToShow[17].title" sortable="filterAttributesToShow[17].field" ng-if="dynamicColumnsVisibility[17]"><%row[filterAttributesToShow[17].field]%></td>
                <td data-title="filterAttributesToShow[18].title" sortable="filterAttributesToShow[18].field" ng-if="dynamicColumnsVisibility[18]"><%row[filterAttributesToShow[18].field]%></td>
                <td data-title="filterAttributesToShow[19].title" sortable="filterAttributesToShow[19].field" ng-if="dynamicColumnsVisibility[19]"><%row[filterAttributesToShow[19].field]%></td>
                <td data-title="filterAttributesToShow[20].title" sortable="filterAttributesToShow[20].field" ng-if="dynamicColumnsVisibility[20]"><%row[filterAttributesToShow[20].field]%></td>
                <td data-title="filterAttributesToShow[21].title" sortable="filterAttributesToShow[21].field" ng-if="dynamicColumnsVisibility[21]"><%row[filterAttributesToShow[21].field]%></td>
                <td data-title="filterAttributesToShow[22].title" sortable="filterAttributesToShow[22].field" ng-if="dynamicColumnsVisibility[22]"><%row[filterAttributesToShow[22].field]%></td>
            </tr>
        </table>
        <div style="clear:both;"></div>
    </div>
    <script type="text/ng-template" id="detail.html">
        <div class="modal-header">
            <h3 class="modal-title"><%item.name%></h3>
        </div>
        <div class="modal-body">
            <style>
                ul { padding:0px;margin:0px; }
                ul li { float:left; width:50%; line-height:30px; border-bottom:1px solid #ccc; }
            </style>
            <ul>
                <li>
                    <div class="row">
                        <div class="col-md-6">Symbol</div>
                        <div class="col-md-6"><% item.symbol; %></div>
                    </div>
                </li>
                <li>
                    <div class="row">
                        <div class="col-md-6">Rank</div>
                         <div class="col-md-6"><% item.rank; %></div>
                     </div>
                </li>
                <li>
                    <div class="row">
                        <div class="col-md-6">Exchange</div>
                        <div class="col-md-6"><% item.exchange; %></div>
                    </div>
                </li>
                <li>
                    <div class="row">
                        <div class="col-md-6">Industry</div>
                        <div class="col-md-6"><% item.industry; %></div>
                    </div>
                </li>
                <li>
                    <div class="row">
                        <div class="col-md-6">Equal-Weight All Factor Average</div>
                        <div class="col-md-6"><% calcWeight(item); %></div>
                    </div>
                </li>
                <li ng-repeat="field in filterAttributes">
                    <div class="row">
                        <div class="col-md-6"><% field.title; %></div>
                        <div class="col-md-6"><% item[field.field]; %></div>
                    </div>
                </li>
            </ul>
            <div style="clear:both;"></div>
        </div>
    </script>
</div>
@endsection
