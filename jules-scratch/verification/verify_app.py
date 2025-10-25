from playwright.sync_api import sync_playwright

def run(playwright):
    browser = playwright.chromium.launch()
    context = browser.new_context()
    page = context.new_page()

    # Login
    page.goto("http://localhost:8000/admin/login")
    page.wait_for_load_state('networkidle') # Wait for all network activity to cease
    page.fill("input[name=username]", "admin")
    page.fill("input[name=password]", "password")
    page.click("button[type=submit]")
    page.wait_for_url("http://localhost:8000/admin")
    page.screenshot(path="jules-scratch/verification/admin-dashboard.png")

    # Verify widget
    page.goto("http://localhost:8000/widget")
    page.wait_for_load_state('networkidle')
    page.click("#chat-bubble")
    page.wait_for_selector("#chat-window-widget")
    page.fill("#chat-widget-input", "Hello from Playwright!")
    page.click("#chat-widget-send")
    page.wait_for_timeout(1000) # Wait for message to appear
    page.screenshot(path="jules-scratch/verification/widget.png")

    browser.close()

with sync_playwright() as playwright:
    run(playwright)
