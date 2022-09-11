<?php

return [

    'API_AUTH'=>env('API_AUTH','mz-auth-nginx'),

    'API_DIRECTORY'=>env('API_DIRECTORY','mz-directory-nginx'),

    'API_PROJECTS'=>env('API_PROJECTS','mz-project-nginx'),

    'API_CONTRACTS'=>env('API_CONTRACTS','mz-contract-nginx'),

    'API_HR'=>env('API_HR','mz_hr_service_nginx'),

    'API_PRODUCTION'=>env('API_PRODUCTION','mz_production_service_nginx'),

    'API_SECURITY'=>env('API_SECURITY','mz_security_service_nginx'),

    'API_INTERVIEW'=>env('API_INTERVIEW','mz_interview_service_nginx'),

    'API_DATA_AGGREGATION'=>env('API_DATA_AGGREGATION','mz_data_aggregation_service_nginx'),

];
