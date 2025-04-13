from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.common.keys import Keys
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC

# Set up the WebDriver
driver = webdriver.Chrome()

try:
    driver.get('http://localhost:8000/login.php')
    
    username_field = driver.find_element(By.NAME, 'username')
    password_field = driver.find_element(By.NAME, 'password')
    username_field.send_keys('Testing')  
    password_field.send_keys('ValidPassword1234')
    password_field.send_keys(Keys.RETURN)
    
    WebDriverWait(driver, 10).until(EC.url_changes('http://localhost:8000/login.php'))
    
    submit_ticket_button = WebDriverWait(driver, 10).until(
        EC.presence_of_element_located((By.ID, 'createPost')))
    submit_ticket_button.click()

    ticket_title = driver.find_element(By.NAME, 'title')
    ticket_description = driver.find_element(By.NAME, 'content')
    ticket_title.send_keys('Test Ticket Title')
    ticket_description.send_keys('This is a test ticket description.')
    
    submit_button = driver.find_element(By.NAME, 'submit')
    submit_button.click()

    WebDriverWait(driver, 10).until(
        EC.presence_of_element_located((By.ID, 'success_message')))
    
    success_message = driver.find_element(By.ID, 'success_message').text
    assert "Post created successfully!" in success_message, "Post submission failed"

    print("Ticket submission test passed!✅")

finally:
    driver.quit()
