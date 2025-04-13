from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.common.keys import Keys
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
import time

# Set up the Selenium WebDriver
driver = webdriver.Chrome()
driver.get("http://localhost:8000/register.php")

# Wait for the page to load
WebDriverWait(driver, 10).until(EC.presence_of_element_located((By.NAME, "username")))

# Test: Valid Registration
def test_valid_registration():
    driver.get("http://localhost:8000/register.php")
    
    # Fill out the registration form
    driver.find_element(By.NAME, "username").send_keys("Testing")
    driver.find_element(By.NAME, "password").send_keys("ValidPassword1234")
    driver.find_element(By.NAME, "confirm_password").send_keys("ValidPassword1234")
    
    # Submit the form
    driver.find_element(By.CSS_SELECTOR, "button[type='submit']").click()
    
    # Wait for the success message (you may need to adjust the expected success message)
    time.sleep(2)
    
    # Check if the user was successfully registered (success page check or message)
    success_message = driver.page_source
    assert "User registered successfully!" in success_message, "Registration failed or success message not found"

# Test: Password Mismatch
def test_password_mismatch():
    driver.get("http://localhost:8000/register.php")
    
    # Fill out the registration form with mismatched passwords
    driver.find_element(By.NAME, "username").send_keys("Testing")
    driver.find_element(By.NAME, "password").send_keys("ValidPassword1234")
    driver.find_element(By.NAME, "confirm_password").send_keys("WrongPassword1234")
    
    # Submit the form
    driver.find_element(By.CSS_SELECTOR, "button[type='submit']").click()
    
    # Wait for the error message about password mismatch
    time.sleep(2)
    
    # Check if the error message is displayed
    error_message = driver.page_source
    assert "Passwords do not match!" in error_message, "Password mismatch error not found"

# Running the tests
try:
    test_valid_registration()
    test_password_mismatch()
    print("✅All tests Passed")
except AssertionError as e:
    print("❌ Test failed:", e)
finally:
    driver.quit()
