from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.common.keys import Keys
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC

# Set up the WebDriver
driver = webdriver.Chrome()

try:
    # Step 1: Navigate to login page
    driver.get('http://localhost:8000/login.php')
    
    # Step 2: Log in with valid credentials
    username_field = driver.find_element(By.NAME, 'username')
    password_field = driver.find_element(By.NAME, 'password')
    username_field.send_keys('Testing')  # Replace with actual username
    password_field.send_keys('ValidPassword1234')  # Replace with actual password
    password_field.send_keys(Keys.RETURN)
    
    # Step 3: Wait for login to complete and redirect to dashboard or home page
    WebDriverWait(driver, 10).until(EC.url_changes('http://localhost:8000/login.php'))
    
    # Step 4: Navigate to the Submit Ticket page (e.g., link to submit ticket)
    submit_ticket_button = WebDriverWait(driver, 10).until(
        EC.presence_of_element_located((By.ID, 'createPost')))
    submit_ticket_button.click()

    # Step 5: Fill out the ticket submission form
    ticket_title = driver.find_element(By.NAME, 'title')
    ticket_description = driver.find_element(By.NAME, 'content')
    ticket_title.send_keys('Test Ticket Title')
    ticket_description.send_keys('This is a test ticket description.')
    
    # Step 6: Submit the ticket
    submit_button = driver.find_element(By.NAME, 'submit')
    submit_button.click()

    # Step 7: Wait for ticket submission confirmation
    WebDriverWait(driver, 10).until(
        EC.presence_of_element_located((By.ID, 'success_message')))
    
    # Step 8: Verify success message
    success_message = driver.find_element(By.ID, 'success_message').text
    assert "Post created successfully!" in success_message, "Post submission failed"

    print("Ticket submission test passed!✅")

finally:
    driver.quit()
