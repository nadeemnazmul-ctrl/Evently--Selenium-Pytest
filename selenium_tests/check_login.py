from selenium import webdriver
from selenium.webdriver.common.by import By
import time

driver = webdriver.Chrome()
driver.maximize_window()

driver.get("http://localhost/evently/login.php")

print("Page title:", driver.title)
print("Current URL:", driver.current_url)

print("Email fields:", len(driver.find_elements(By.ID, "loginEmail")))
print("Password fields:", len(driver.find_elements(By.ID, "loginPassword")))
print("Login buttons:", len(driver.find_elements(By.CSS_SELECTOR, "button.btn.full")))

time.sleep(5)

driver.quit()