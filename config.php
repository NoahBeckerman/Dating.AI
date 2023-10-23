<?php
// Database credentials
define('DB_HOST', '127.0.0.1');
define('DB_USER', 'BHS');
define('DB_PASS', 'BHS');
define('DB_NAME', 'datingai');

define('OPENAI_API_KEY', '');;
define('TEMPERATURE', 0.7)

/**
 * OpenAI API Pricing and Token Limitations
 * 
 * This section outlines the cost and token limitations for various OpenAI GPT models.
 * 
 * GPT-4 32k Model:
 * - Model Identifier: gpt-4-32k
 * - Cost per prompt: USD 0.0600
 * - Cost per completion: USD 0.1200
 * - Token Limit: 32768
 * - Interactive: No
 * 
 * GPT-4 Model:
 * - Model Identifier: gpt-4
 * - Cost per prompt: USD 0.0300
 * - Cost per completion: USD 0.0600
 * - Token Limit: 8192
 * - Interactive: No
 * 
 * GPT-3.5 16k Model:
 * - Model Identifier: gpt-3.5-turbo-16k
 * - Cost per prompt: USD 0.0030
 * - Cost per completion: USD 0.0040
 * - Token Limit: 16384
 * - Interactive: No
 * 
 * GPT-3.5 Model:
 * - Model Identifier: gpt-3.5-turbo
 * - Cost per prompt: USD 0.0015
 * - Cost per completion: USD 0.0020
 * - Token Limit: 4096
 * - Interactive: No
 * 
 * Davinci Model:
 * - Model Identifier: text-davinci-003
 * - Cost: USD 0.0200
 * - Token Limit: 4096
 * - Interactive: Yes
 * 
 * Curie Model:
 * - Model Identifier: text-curie-001
 * - Cost: USD 0.0020
 * - Token Limit: 2049
 * - Interactive: Yes
 * 
 * Babbage Model:
 * - Model Identifier: text-babbage-001
 * - Cost: USD 0.0005
 * - Token Limit: 2049
 * - Interactive: Yes
 * 
 * Ada Model:
 * - Model Identifier: text-ada-001
 * - Cost: USD 0.0004
 * - Token Limit: 2049
 * - Interactive: Yes
 * 
 * Note: The above information is crucial for selecting the appropriate model for different use-cases based on cost and token limitations.
 */

?>

