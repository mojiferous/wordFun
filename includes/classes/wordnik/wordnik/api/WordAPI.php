<?php
/**
 *  Copyright 2011 Wordnik, Inc.
 *
 *  Licensed under the Apache License, Version 2.0 (the "License");
 *  you may not use this file except in compliance with the License.
 *  You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 *  Unless required by applicable law or agreed to in writing, software
 *  distributed under the License is distributed on an "AS IS" BASIS,
 *  WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 *  See the License for the specific language governing permissions and
 *  limitations under the License.
 */
 
/**
 *
 * NOTE: This class is auto generated by the swagger code generator program. Do not edit the class manually.
 */

class WordAPI {

	function __construct($apiClient) {
	  $this->apiClient = $apiClient;
	}


	/**
	 * Returns examples for a word
	 *
	 * 
	 * 
   * @param wordExamplesInput  
   *  
	 * @return ExampleSearchResults {@link ExampleSearchResults} 
	 * @throws APIException 400 - Invalid word supplied. 
	 */

	 public function getExamples($wordExamplesInput) {

		//parse inputs
		$resourcePath = "/word.{format}/{word}/examples";
		$resourcePath = str_replace("{format}", "json", $resourcePath);
		$method = "GET";
        $queryParams = array();
        $headerParams = array();
    
	
		
		if($wordExamplesInput != null && $wordExamplesInput->includeDuplicates != null) {
		 	$queryParams["includeDuplicates"] = $this->apiClient->toPathValue($wordExamplesInput->includeDuplicates);
		}
		if($wordExamplesInput != null && $wordExamplesInput->contentProvider != null) {
		 	$queryParams["contentProvider"] = $this->apiClient->toPathValue($wordExamplesInput->contentProvider);
		}
		if($wordExamplesInput != null && $wordExamplesInput->useCanonical != null) {
		 	$queryParams["useCanonical"] = $this->apiClient->toPathValue($wordExamplesInput->useCanonical);
		}
		if($wordExamplesInput != null && $wordExamplesInput->skip != null) {
		 	$queryParams["skip"] = $this->apiClient->toPathValue($wordExamplesInput->skip);
		}
		if($wordExamplesInput != null && $wordExamplesInput->limit != null) {
		 	$queryParams["limit"] = $this->apiClient->toPathValue($wordExamplesInput->limit);
		}

		if($wordExamplesInput != null && $wordExamplesInput->word != null) {
		 	$resourcePath = str_replace("{word}", $wordExamplesInput->word, $resourcePath);	
		}

	

		//make the API Call
		$response = $this->apiClient->callAPI($resourcePath, $method, $queryParams, null, $headerParams);
    if(! $response){
        return null;
    }

		//create output objects if the response has more than one object
		$responseObject = $this->apiClient->deserialize($response, 'ExampleSearchResults');
		return $responseObject;
				
				
	 }


	/**
	 * Given a word as a string, returns the WordObject that represents it
	 *
	 * 
	 * 
   * @param word  String value of WordObject to return
   *  @param useCanonical  If true will try to return the correct word root ('cats' -> 'cat'). If false returns exactly what was requested.
   * 	 *      Allowed values are - false,true  @param includeSuggestions  Return suggestions (for correct spelling, case variants, etc.)
   * 	 *      Allowed values are - false,true  
	 * @return WordObject {@link WordObject} 
	 * @throws APIException 400 - Invalid word supplied. 
	 */

	 public function getWord($word, $useCanonical, $includeSuggestions) {

		//parse inputs
		$resourcePath = "/word.{format}/{word}";
		$resourcePath = str_replace("{format}", "json", $resourcePath);
		$method = "GET";
        $queryParams = array();
        $headerParams = array();
    
		
    if($useCanonical != null) {
		$queryParams['useCanonical'] = $this->apiClient->toPathValue($useCanonical);
	}
    if($includeSuggestions != null) {
		$queryParams['includeSuggestions'] = $this->apiClient->toPathValue($includeSuggestions);
	}

		if($word != null) {
			$resourcePath = str_replace("{word}", $word, $resourcePath);
		}

	
	

		//make the API Call
		$response = $this->apiClient->callAPI($resourcePath, $method, $queryParams, null, $headerParams);
    if(! $response){
        return null;
    }

		//create output objects if the response has more than one object
		$responseObject = $this->apiClient->deserialize($response, 'WordObject');
		return $responseObject;
				
				
	 }


	/**
	 * Return definitions for a word
	 *
	 * 
	 * 
   * @param wordDefinitionsInput  
   *  
	 * @return Array<Definition> {@link Definition} 
	 * @throws APIException 400 - Invalid word supplied. 404 - No definitions found. 
	 */

	 public function getDefinitions($wordDefinitionsInput) {

		//parse inputs
		$resourcePath = "/word.{format}/{word}/definitions";
		$resourcePath = str_replace("{format}", "json", $resourcePath);
		$method = "GET";
        $queryParams = array();
        $headerParams = array();
    
	
		
		if($wordDefinitionsInput != null && $wordDefinitionsInput->limit != null) {
		 	$queryParams["limit"] = $this->apiClient->toPathValue($wordDefinitionsInput->limit);
		}
		if($wordDefinitionsInput != null && $wordDefinitionsInput->partOfSpeech != null) {
		 	$queryParams["partOfSpeech"] = $this->apiClient->toPathValue($wordDefinitionsInput->partOfSpeech);
		}
		if($wordDefinitionsInput != null && $wordDefinitionsInput->includeRelated != null) {
		 	$queryParams["includeRelated"] = $this->apiClient->toPathValue($wordDefinitionsInput->includeRelated);
		}
		if($wordDefinitionsInput != null && $wordDefinitionsInput->sourceDictionaries != null) {
		 	$queryParams["sourceDictionaries"] = $this->apiClient->toPathValue($wordDefinitionsInput->sourceDictionaries);
		}
		if($wordDefinitionsInput != null && $wordDefinitionsInput->useCanonical != null) {
		 	$queryParams["useCanonical"] = $this->apiClient->toPathValue($wordDefinitionsInput->useCanonical);
		}
		if($wordDefinitionsInput != null && $wordDefinitionsInput->includeTags != null) {
		 	$queryParams["includeTags"] = $this->apiClient->toPathValue($wordDefinitionsInput->includeTags);
		}

		if($wordDefinitionsInput != null && $wordDefinitionsInput->word != null) {
		 	$resourcePath = str_replace("{word}", $wordDefinitionsInput->word, $resourcePath);	
		}

	

		//make the API Call
		$response = $this->apiClient->callAPI($resourcePath, $method, $queryParams, null, $headerParams);
    if(! $response){
        return null;
    }

		
        $responseObjects = array();
        foreach ($response as $object) {
          $responseObjects[] = $this->apiClient->deserialize($object, 'Definition');
        }
        return $responseObjects;				
	 }


	/**
	 * Returns a top example for a word
	 *
	 * 
	 * 
   * @param word  Word to fetch examples for
   *  @param contentProvider  Return results from a specific ContentProvider
   *  @param useCanonical  If true will try to return the correct word root ('cats' -> 'cat'). If false returns exactly what was requested.
   * 	 *      Allowed values are - false,true  
	 * @return Example {@link Example} 
	 * @throws APIException 400 - Invalid word supplied. 
	 */

	 public function getTopExample($word, $contentProvider, $useCanonical) {

		//parse inputs
		$resourcePath = "/word.{format}/{word}/topExample";
		$resourcePath = str_replace("{format}", "json", $resourcePath);
		$method = "GET";
        $queryParams = array();
        $headerParams = array();
    
		
    if($contentProvider != null) {
		$queryParams['contentProvider'] = $this->apiClient->toPathValue($contentProvider);
	}
    if($useCanonical != null) {
		$queryParams['useCanonical'] = $this->apiClient->toPathValue($useCanonical);
	}

		if($word != null) {
			$resourcePath = str_replace("{word}", $word, $resourcePath);
		}

	
	

		//make the API Call
		$response = $this->apiClient->callAPI($resourcePath, $method, $queryParams, null, $headerParams);

    if(! $response){
        return null;
    }

		//create output objects if the response has more than one object
		$responseObject = $this->apiClient->deserialize($response, 'Example');
		return $responseObject;
				
				
	 }


	/**
	 * Returns text pronunciations for a given word
	 *
	 * 
	 * 
   * @param wordPronunciationsInput  
   *  
	 * @return Array<TextPron> {@link TextPron} 
	 * @throws APIException 400 - Invalid word supplied. 
	 */

	 public function getTextPronunciations($wordPronunciationsInput) {

		//parse inputs
		$resourcePath = "/word.{format}/{word}/pronunciations";
		$resourcePath = str_replace("{format}", "json", $resourcePath);
		$method = "GET";
        $queryParams = array();
        $headerParams = array();
    
	
		
		if($wordPronunciationsInput != null && $wordPronunciationsInput->useCanonical != null) {
		 	$queryParams["useCanonical"] = $this->apiClient->toPathValue($wordPronunciationsInput->useCanonical);
		}
		if($wordPronunciationsInput != null && $wordPronunciationsInput->sourceDictionary != null) {
		 	$queryParams["sourceDictionary"] = $this->apiClient->toPathValue($wordPronunciationsInput->sourceDictionary);
		}
		if($wordPronunciationsInput != null && $wordPronunciationsInput->typeFormat != null) {
		 	$queryParams["typeFormat"] = $this->apiClient->toPathValue($wordPronunciationsInput->typeFormat);
		}
		if($wordPronunciationsInput != null && $wordPronunciationsInput->limit != null) {
		 	$queryParams["limit"] = $this->apiClient->toPathValue($wordPronunciationsInput->limit);
		}

		if($wordPronunciationsInput != null && $wordPronunciationsInput->word != null) {
		 	$resourcePath = str_replace("{word}", $wordPronunciationsInput->word, $resourcePath);	
		}

	

		//make the API Call
		$response = $this->apiClient->callAPI($resourcePath, $method, $queryParams, null, $headerParams);
    if(! $response){
        return null;
    }

		
        $responseObjects = array();
        foreach ($response as $object) {
          $responseObjects[] = $this->apiClient->deserialize($object, 'TextPron');
        }
        return $responseObjects;				
	 }


	/**
	 * Returns syllable information for a word
	 *
	 * 
	 * 
   * @param word  Word to get syllables for
   *  @param useCanonical  If true will try to return a correct word root ('cats' -> 'cat'). If false returns exactly what was requested.
   *  @param sourceDictionary  Get from a single dictionary. Valid options: ahd, century, wiktionary, webster, and wordnet.
   *  @param limit  Maximum number of results to return
   *  
	 * @return Array<Syllable> {@link Syllable} 
	 * @throws APIException 400 - Invalid word supplied. 
	 */

	 public function getHyphenation($word, $useCanonical, $sourceDictionary, $limit) {

		//parse inputs
		$resourcePath = "/word.{format}/{word}/hyphenation";
		$resourcePath = str_replace("{format}", "json", $resourcePath);
		$method = "GET";
        $queryParams = array();
        $headerParams = array();
    
		
    if($useCanonical != null) {
		$queryParams['useCanonical'] = $this->apiClient->toPathValue($useCanonical);
	}
    if($sourceDictionary != null) {
		$queryParams['sourceDictionary'] = $this->apiClient->toPathValue($sourceDictionary);
	}
    if($limit != null) {
		$queryParams['limit'] = $this->apiClient->toPathValue($limit);
	}

		if($word != null) {
			$resourcePath = str_replace("{word}", $word, $resourcePath);
		}

	
	

		//make the API Call
		$response = $this->apiClient->callAPI($resourcePath, $method, $queryParams, null, $headerParams);
    if(! $response){
        return null;
    }

		
        $responseObjects = array();
        foreach ($response as $object) {
          $responseObjects[] = $this->apiClient->deserialize($object, 'Syllable');
        }
        return $responseObjects;				
	 }


	/**
	 * Returns word usage over time
	 *
	 * 
	 * 
   * @param word  Word to return
   *  @param useCanonical  If true will try to return the correct word root ('cats' -> 'cat'). If false returns exactly what was requested.
   *  @param startYear  Starting Year
   *  @param endYear  Ending Year
   *  
	 * @return FrequencySummary {@link FrequencySummary} 
	 * @throws APIException 400 - Invalid word supplied. 404 - No results. 
	 */

	 public function getWordFrequency($word, $useCanonical, $startYear, $endYear) {

		//parse inputs
		$resourcePath = "/word.{format}/{word}/frequency";
		$resourcePath = str_replace("{format}", "json", $resourcePath);
		$method = "GET";
        $queryParams = array();
        $headerParams = array();
    
		
    if($useCanonical != null) {
		$queryParams['useCanonical'] = $this->apiClient->toPathValue($useCanonical);
	}
    if($startYear != null) {
		$queryParams['startYear'] = $this->apiClient->toPathValue($startYear);
	}
    if($endYear != null) {
		$queryParams['endYear'] = $this->apiClient->toPathValue($endYear);
	}

		if($word != null) {
			$resourcePath = str_replace("{word}", $word, $resourcePath);
		}

	
	

		//make the API Call
		$response = $this->apiClient->callAPI($resourcePath, $method, $queryParams, null, $headerParams);
    if(! $response){
        return null;
    }

		//create output objects if the response has more than one object
		$responseObject = $this->apiClient->deserialize($response, 'FrequencySummary');
		return $responseObject;
				
				
	 }


	/**
	 * Fetches bi-gram phrases for a word
	 *
	 * 
	 * 
   * @param word  Word to fetch phrases for
   *  @param limit  Maximum number of results to return
   *  @param wlmi  Minimum WLMI for the phrase
   *  @param useCanonical  If true will try to return the correct word root ('cats' -> 'cat'). If false returns exactly what was requested.
   * 	 *      Allowed values are - false,true  
	 * @return Array<Bigram> {@link Bigram} 
	 * @throws APIException 400 - Invalid word supplied. 
	 */

	 public function getPhrases($word, $limit, $wlmi, $useCanonical) {

		//parse inputs
		$resourcePath = "/word.{format}/{word}/phrases";
		$resourcePath = str_replace("{format}", "json", $resourcePath);
		$method = "GET";
        $queryParams = array();
        $headerParams = array();
    
		
    if($limit != null) {
		$queryParams['limit'] = $this->apiClient->toPathValue($limit);
	}
    if($wlmi != null) {
		$queryParams['wlmi'] = $this->apiClient->toPathValue($wlmi);
	}
    if($useCanonical != null) {
		$queryParams['useCanonical'] = $this->apiClient->toPathValue($useCanonical);
	}

		if($word != null) {
			$resourcePath = str_replace("{word}", $word, $resourcePath);
		}

	
	

		//make the API Call
		$response = $this->apiClient->callAPI($resourcePath, $method, $queryParams, null, $headerParams);
    if(! $response){
        return null;
    }

		
        $responseObjects = array();
        foreach ($response as $object) {
          $responseObjects[] = $this->apiClient->deserialize($object, 'Bigram');
        }
        return $responseObjects;				
	 }


	/**
	 * Return related words (thesaurus data) for a word
	 *
	 * 
	 * 
   * @param wordRelatedInput  
   *  
	 * @return Array<Related> {@link Related} 
	 * @throws APIException 400 - Invalid word supplied. 404 - No definitions found. 
	 */

	 public function getRelated($wordRelatedInput) {

		//parse inputs
		$resourcePath = "/word.{format}/{word}/related";
		$resourcePath = str_replace("{format}", "json", $resourcePath);
		$method = "GET";
        $queryParams = array();
        $headerParams = array();
    
	
		
		if($wordRelatedInput != null && $wordRelatedInput->partOfSpeech != null) {
		 	$queryParams["partOfSpeech"] = $this->apiClient->toPathValue($wordRelatedInput->partOfSpeech);
		}
		if($wordRelatedInput != null && $wordRelatedInput->sourceDictionary != null) {
		 	$queryParams["sourceDictionary"] = $this->apiClient->toPathValue($wordRelatedInput->sourceDictionary);
		}
		if($wordRelatedInput != null && $wordRelatedInput->limit != null) {
		 	$queryParams["limit"] = $this->apiClient->toPathValue($wordRelatedInput->limit);
		}
		if($wordRelatedInput != null && $wordRelatedInput->useCanonical != null) {
		 	$queryParams["useCanonical"] = $this->apiClient->toPathValue($wordRelatedInput->useCanonical);
		}
		if($wordRelatedInput != null && $wordRelatedInput->type != null) {
		 	$queryParams["type"] = $this->apiClient->toPathValue($wordRelatedInput->type);
		}

		if($wordRelatedInput != null && $wordRelatedInput->word != null) {
		 	$resourcePath = str_replace("{word}", $wordRelatedInput->word, $resourcePath);	
		}

	

		//make the API Call
		$response = $this->apiClient->callAPI($resourcePath, $method, $queryParams, null, $headerParams);
    if(! $response){
        return null;
    }

		
        $responseObjects = array();
        foreach ($response as $object) {
          $responseObjects[] = $this->apiClient->deserialize($object, 'Related');
        }
        return $responseObjects;				
	 }


	/**
	 * Fetches audio metadata for a word.
	 *
	 * The metadata includes a time-expiring fileUrl which allows reading the audio file directly from the API.  Currently only audio pronunciations from the American Heritage Dictionary in mp3 format are supported.
	 * 
   * @param word  Word to get audio for.
   *  @param useCanonical  Use the canonical form of the word.
   *  @param limit  Maximum number of results to return
   *  
	 * @return Array<AudioFile> {@link AudioFile} 
	 * @throws APIException 400 - Invalid word supplied. 
	 */

	 public function getAudio($word, $useCanonical, $limit) {

		//parse inputs
		$resourcePath = "/word.{format}/{word}/audio";
		$resourcePath = str_replace("{format}", "json", $resourcePath);
		$method = "GET";
        $queryParams = array();
        $headerParams = array();
    
		
    if($useCanonical != null) {
		$queryParams['useCanonical'] = $this->apiClient->toPathValue($useCanonical);
	}
    if($limit != null) {
		$queryParams['limit'] = $this->apiClient->toPathValue($limit);
	}

		if($word != null) {
			$resourcePath = str_replace("{word}", $word, $resourcePath);
		}

	
	

		//make the API Call
		$response = $this->apiClient->callAPI($resourcePath, $method, $queryParams, null, $headerParams);
    if(! $response){
        return null;
    }

		
        $responseObjects = array();
        foreach ($response as $object) {
          $responseObjects[] = $this->apiClient->deserialize($object, 'AudioFile');
        }
        return $responseObjects;				
	 }



}