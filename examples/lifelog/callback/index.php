<?php
/*
 * Copyright 2015 Mikael Johansson, www.complexit.se
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
 
/**
 * This file is used as Lifelog API authorization callback.
 * 
 * Enter this file's URL when creating the app at:
 * https://developer.sony.com/develop/services/lifelog-api/create-app/
 * 
 * Lifelog authentication help:
 * See https://developer.sony.com/develop/services/lifelog-api/authentication/
 * 
 * 
 * 
 **/
 
/*
 * Example of authorization paramters (when accepting that the app has access to lifelog scopes):
 * scope=lifelog.profile.read%20lifelog.activities.read%20lifelog.locations.read&state=null&code=01VR3R0Y
 */
session_start();
header("Location: ../index.php?" .$_SERVER['QUERY_STRING']);

?>
