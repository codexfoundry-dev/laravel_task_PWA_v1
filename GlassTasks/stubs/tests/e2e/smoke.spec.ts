import { test, expect } from '@playwright/test'

test('login and create task', async ({ page }) => {
  await page.goto('/')
  // Assumes Breeze auth pages exist; this is a placeholder.
  await expect(page).toHaveTitle(/GlassTasks/i)
})