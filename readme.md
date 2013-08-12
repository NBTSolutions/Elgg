# Elgg WeatherBlur REST API #
---
*JSONP not supported

### Login/Get Investigations ###
Method: GET<br>
URL: <br>
`http://<domain>/elgg/services/api/rest/json/`

URL Params:<br>
`?method=wb.get_invs&username=blah&password=blah`

Full URL:<br>
`http://<domain>/elgg/services/api/rest/json/?method=wb.get_invs&username=blah&password=blah`

**get_invs**

    parameters
    	method : wb.get_inv,
        username : blah,
        password : blah

    returns
        {
            status : <int>,
            results : {
                user_guid : <int>,
                username: <string>,
                token : <string>,
                invs : [
                   {
                        name : <string>,
                        guid : <int>
                   },
                   {
                        name : <string>,
                        guid : <int>
                   }
                ]
            }
        }

### Create Observation ###
Method: GET<br>
URL:<br>
`http://<domain>/elgg/services/api/rest/json/`

URL Params:<br>
`?method=wb.create_obs&inv_guid=42&token=b8a0d67b16669580eaabf979454b93ae&agg_id=10`

Full URL:<br>
`http://<domain>/elgg/services/api/rest/json/?method=wb.create_obs&inv_guid=42&token=b8a0d67b16669580eaabf979454b93ae&agg_id=10`

**create_obs**

    parameters
    	method : wb.create_obs,
        inv_guid : 42,
        agg_id : 10,
        token : b8a0d67b16669580eaabf979454b93ae

    returns
        {
            status : <int>,
            results : <obs_guid>
        }

### Get All Observations ###
Method: GET<br>
URL:<br>
`http://<domain>/elgg/services/api/rest/json/`

URL Params:<br>
`?method=wb.get_obs`

Full URL:<br>
`http://<domain>/elgg/services/api/rest/json/?method=wb.get_obs`

**create_obs**

    parameters
    	method : wb.get_obs

    returns
        {
    "status": 0,
    "result": [
        	{
            	"name": "john",
            	"obs_guid": 135,
            	"agg_id": "6ea91dea-eda9-49f4-ae05-2c972e76a395",
            	"time_created": "1376056053"
        	},
        	{
        		…
        	}
		}

### Get Observation by User Type ###
Method: GET<br>
URL:<br>
`http://<domain>/elgg/services/api/rest/json/`

URL Params:<br>
`?method=wb.get_obs_by_user_type&user_type=Student&min_date=1374690933&max_date=1374691000`

Full URL:<br>
`http://<domain>/elgg/services/api/rest/json/?method=wb.get_obs_by_user_type&user_type=Student&min_date=1374690933&max_date=1374691000`

**get_obs_by_user_type**

    parameter
    	method : wb.get_obs_by_user_type,
        user_type : Student,
        min_date : <epoch>
        max_data : <epoch>

    valid user_types are:
        "Student", "Teacher", "Scientist", "Fisherman", "Community Member", 

    returns 
        {
            "status" : <int>,
            "results" : [
            	{
            		"agg_ids" : <int>, 
                	"user_display_name" : <string>,
                	"username" : <string>,
                	"user_guid" : <int>
            	},
            	{
            		"agg_ids" : <int>, 
                	"user_display_name" : <string>,
                	"username" : <string>,
                	"user_guid" : <int>	
            	}
            ]
        }

### Get Observation by Investigation (work in progress)###
Method: GET<br>
URL:<br>
`http://<domain>/elgg/services/api/rest/json/`

URL Params:<br>
`?method=wb.get_obs_by_inv&inv_id=43`

Full URL:<br>
`http://localhost:9999/elgg/services/api/rest/json/?method=wb.get_obs_by_inv&inv_id=43`

**get_obs_by_user_type**

    parameter
    	method : wb.get_obs_by_inv,
        inv_id : 43

    returns 
        {
        	"status" : <int>
        	"results" : [
				{
					"time_created" : <int>,
					"agg_id": <int>,
					"time_created": <epoch>
				},
				{
					"time_created" : <int>,
					"agg_id": <int>,
					"time_created": <epoch>
				}
			]
        }

### Toggle Like on Observation ###
Method: GET<br>
URL:<br>
`http://<domain>/elgg/services/api/rest/json/`

URL Params:<br>
`?method=wb.toggle_like_obs&observation_guid=50`

Full URL:<br>
`http://localhost:9999/elgg/services/api/rest/json/?method=wb.toggle_like_obs&observation_guid=50`

**toggle_like_obs**

    parameter
        method : wb.toggle_like_obs,
        observation_guid : 50

	returns
		{
    		"status": <int>,
    		"result": {
        		"all_likes": <int>
    		}
		}

### Toggle Like on Observation By Agg Id
Method: GET<br>
URL:<br>
`http://<domain>/elgg/service/api/rest/json/`

URL Params:<br>
`?method=wb.toggle_like_obs_by_agg_id&agg_id=df7ed694-c6c8-424f-b81e-fc54496f2b6d`

FULL URL:<br>
`http://<domain>/elgg/services/api/rest/json/?method=wb.toggle_like_obs_by_agg_id&agg_id=df7ed694-c6c8-424f-b81e-fc54496f2b6d`

**toggle_like_obs_by_agg_id**

    parameter
        method : wb.toggle_like_obs,
        agg_id : df7ed694-c6c8-424f-b81e-fc54496f2b6d

	returns
		{
    		"status": <int>,
    		"result": {
        		"all_likes": <int>
    		}
		}
	

### Get Likes on an Observation ###
Method: GET<br>
URL:<br>
`http://<domain>/elgg/services/api/rest/json/`

URL Params:<br>
`?method=wb.get_likes&observation_guid=52`

Full URL:<br>
`http://<domain>/elgg/services/api/rest/json/?method=wb.get_likes&observation_guid=52`

**get_likes**

	parameter
		method : wb.get_likes,
		observation_guid : 50
	
	returns
		{
    		"status": 0,
    		"result": {
        		"all_likes": <int>
    		}
		}

### Get Likes on an Observation By Agg Id###
Method: GET<br>
URL:<br>
`http://<domain>/elgg/services/api/rest/json/`

URL Params:<br>
`?method=wb.get_likes_by_agg_id&agg_id=df7ed694-c6c8-424f-b81e-fc54496f2b6d`

Full URL:<br>
`http://<domain>/elgg/services/api/rest/json/?method=wb.get_likes_by_agg_id&agg_id=df7ed694-c6c8-424f-b81e-fc54496f2b6d`

**get_likes**

	parameter
		method : wb.get_likes,
		agg_id : df7ed694-c6c8-424f-b81e-fc54496f2b6d
	
	returns
		{
    		"status": 0,
    		"result": {
        		"all_likes": <int>
    		}
		}

### Get My Likes on an Observation ###
Method: GET<br>
URL:<br>
`http://<domain>/elgg/services/api/rest/json/`

URL Params:<br>
`?method=wb.get_my_obs_like&observation_guid=142`

Full URL:<br>
`http://<domain>/elgg/services/api/rest/json/?method=wb.get_my_obs_like&observation_guid=142`

**get_likes**

	parameter
		method : wb.get_my_obs_like,
		observation_guid : 50
	
	returns
		{
    		"status": 0,
    		"result": <0 or 1>
    	}

### Get My Likes on an Observation By Agg Id###
Method: GET<br>
URL:<br>
`http://<domain>/elgg/services/api/rest/json/`

URL Params:<br>
`?method=wb.get_my_obs_like_by_agg_id&agg_id=df7ed694-c6c8-424f-b81e-fc54496f2b6d`

Full URL:<br>
`http://localhost:9999/elgg/services/api/rest/json/?method=wb.get_my_obs_like_by_agg_id&agg_id=df7ed694-c6c8-424f-b81e-fc54496f2b6d`

**get_likes**

	parameter
		method : wb.get_my_obs_like,
		observation_guid : df7ed694-c6c8-424f-b81e-fc54496f2b6d
	
	returns
		{
    		"status": 0,
    		"result": <0 or 1>
    	}

### Comment on Observation ###
Method: GET<br>
URL:<br>
`http://<domain>/elgg/services/api/rest/json/`

URL Params:<br>
`?method=wb.comment_on_obs&observation_guid=50&token=614ad70385f0fbda481adb4b32c1bf3a&comment=LOL`

Full URL:<br>
`http://<domain>/elgg/services/api/rest/json/?method=wb.comment_on_obs&observation_guid=50&token=614ad70385f0fbda481adb4b32c1bf3a&comment=LOL`

**comment_on_obs**

	parameter
		method : wb.comment_on_obs,
		observation_guid : 50,
		token : 614ad70385f0fbda481adb4b32c1bf3a
	
	returns
		{
			{
    			"status": <int>,
    			"result": <0 or 1>
			}
		}

### Get Comments on Observation ###
Method: GET<br>
URL:<br>
`http://<domain>/elgg/services/api/rest/json/`

URL Params:<br>
`?method=wb.get_comments_on_obs&observation_guid=50`

Full URL:<br>
`http://<domain>/elgg/services/api/rest/json/?method=wb.get_comments_on_obs&observation_guid=50`

**get_comments_on_obs**

	parameter
		method : wb.get_comments_on_obs,
		observation_guid : 50
		
	returns
		{
		   "status": 0,
    		"result": [
        		{
            		"time_created": <int>,
            		"value": <string>
        		}
    		]
		}

### Get Comments on Observation By Agg Id ###
Method: GET<br>
URL:<br>
`http://<domain>/elgg/services/api/rest/json/`

URL Params:<br>
`?method=wb.get_comments_on_obs_by_agg_id&agg_id=df7ed694-c6c8-424f-b81e-fc54496f2b6d`

Full URL:<br>
`http://localhost:9999/elgg/services/api/rest/json/?method=wb.get_comments_on_obs_by_agg_id&agg_id=df7ed694-c6c8-424f-b81e-fc54496f2b6d`

**get_comments_on_obs**

	parameter
		method : wb.get_comments_on_obs,
		observation_guid : 50
		
	returns
		{
		   "status": 0,
    		"result": [
        		{
            		"time_created": <int>,
            		"value": <string>
        		}
    		]
		}

### Is Logged In ###
Method: GET<br>
URL:<br>
`http://<domain>/elgg/services/api/rest/json/`

URL Params:<br>
`?method=wb.is_logged_in`

Full URL:<br>
`http://<domain>/elgg/services/api/rest/json/?method=wb.is_logged_in`

**is_logged_in**

	parameter
		method : wb.is_loggend_in
	
	returns (result is the user's token if logged in else result is 0)
		{
    		"status": 0,
    		"result": "614ad70385f0fbda481adb4b32c1bf3a"
		}

### get_user_info ###
Method: GET<br>
URL:<br>
`http://localhost:9999/elgg/services/api/rest/json/`

URL Params:<br>
`?method=wb.get_user_info&user_guid=49`

FULL URL:<br>
`http://localhost:9999/elgg/services/api/rest/json/?method=wb.get_user_info&user_guid=49`

**get_user_info**

	parameter
		method : wb.get_user_info,
		user_guid : 49,
		icon_size: <tiny, topbar, small, medium, large, master>
		
	returns 
		{
			"status": 0,
    		"result": {
        		"users_display_name": "John Longanecker",
        		"username": "johnlonganecker",
        		"image": "http://<domain>/elgg/mod/profile/icondirect.php?lastcache=1375285016&joindate=1374596349&amp;guid=37&size=tiny",
        		"email": "john.longanecker@nbtsolutions.com",
        		"profile_type": "Student"
    		}
		}
		
### get_user_info_by_agg_id ###
Method: GET<br>
URL:<br>
`http://localhost:9999/elgg/services/api/rest/json/`

URL Params:<br>
`?method=wb.get_user_info_by_agg_id&agg_id=11`

FULL URL:<br>
`http://localhost:9999/elgg/services/api/rest/json/?method=wb.get_user_info_by_agg_id&agg_id=11`

**get_user_info_by_agg_id**

	parameter
		method : wb.get_user_info_by_agg_id,
		agg_id : 11
		icon_size: <tiny, topbar, small, medium, large, master>
		
	returns
		{
			"status": 0,
    		"result": {
        		"users_display_name": "name",
        		"username": "john",
        		"image": "<domain>/elgg/mod/profile/icondirect.php?lastcache=1375285016&joindate=1374596349&amp;guid=37&size=tiny",
        		"email": "john.longanecker@nbtsolutions.com",
        		"profile_type": "Student"
    		}
		}