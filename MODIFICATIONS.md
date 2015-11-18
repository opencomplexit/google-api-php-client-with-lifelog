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
 
 This project is based on https://github.com/google/google-api-php-client
 
 Some modifications are done in order to get it working with Lifelog.
 The modifications are breifly described below:
 
 src/Auth/OAuth2.php:
  Made URI configurable so it's possible to pass in Lifelog URI's.
  Also added an additional URI for refreshing access token.
  
 src/Config.php:
   Added Lifelog configurations.

 README.md
   Changed to match the modifications.
