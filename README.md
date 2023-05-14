# Promotion engine 
Promotion engine is a project to manage a promotions of your products by Date range multiplier, Even and odd items multiplier and Fixed price Voucher. 

To use this project you should follow these instructions.

 1. Install dependencies
  ```sh
  composer install
   ```
   
 2. Run docker
   ```sh
   docker-compose up -d
   ```
   
 3. Run database command
    - configure your .env file to match the port and container name.
    - In your mysql container use the following command.
   ```sh
   php bin/console d:m:m
   ```
   
 4. Run application
   ```sh
   PHP -S localhost:8000 -t public
   ```

 5. Run Tests
   ```sh
   vendor/bin/phpunit tests/unit/DtoSubscriberTest.php
   vendor/bin/phpunit tests/unit/LowestPriceFilterTest.php
   vendor/bin/phpunit tests/unit/PriceModifiersTest.php
   ```
   
   # Examples: 
   
   1. Success Results 
    
   <img width="1056" alt="image" src="https://github.com/abdelfattahKarchal/promotion-engine/assets/24846752/c7cc8cac-51e6-483b-9390-f144d321c698">
    
   2. Error Results (exception handling)
  
   <img width="1056" alt="image" src="https://github.com/abdelfattahKarchal/promotion-engine/assets/24846752/534a6312-1913-474a-a68d-f6ce5836114d">

