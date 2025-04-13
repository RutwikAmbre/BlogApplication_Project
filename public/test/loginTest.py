from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC

driver = webdriver.Chrome()

# Base URL - adjust according to your local server setup
BASE_URL = "http://localhost:8000/login.php"

# Test: Normal Flow (Successful Login)
def test_normal_login():
    driver.get(BASE_URL)
    WebDriverWait(driver, 10).until(EC.presence_of_element_located((By.ID, "username")))
    driver.find_element(By.ID, "username").send_keys("validUser")
    driver.find_element(By.ID, "password").send_keys("ValidPassword123")
    driver.find_element(By.ID, "login_button").click()

# Test: Alternate Flow (Incorrect Password)
def test_alternate_login_incorrect_password():
    driver.get(BASE_URL)
    WebDriverWait(driver, 10).until(EC.presence_of_element_located((By.ID, "username")))
    driver.find_element(By.ID, "username").send_keys("validUser")
    driver.find_element(By.ID, "password").send_keys("WrongPassword123")
    driver.find_element(By.ID, "login_button").click()

    WebDriverWait(driver, 10).until(EC.presence_of_element_located((By.ID, "error_message")))
    error_message = driver.find_element(By.ID, "error_message").text
    assert "Invalid credentials" in error_message  # Or adjust to match your PHP output

# Run tests
try:
    test_normal_login()
    test_alternate_login_incorrect_password()
    print("✅ All tests passed!")
except AssertionError as e:
    print("❌ Test failed:", e)
finally:
    driver.quit()
