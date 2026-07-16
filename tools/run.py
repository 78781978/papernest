#!/usr/bin/env python3
# -*- coding: utf-8 -*-
import sys, os
sys.path.insert(0, os.path.dirname(os.path.abspath(__file__)))

import pages_home, pages_shop, pages_about, pages_portfolio, pages_contact, pages_legal, pages_account, pages_checkout, pages_accessibility

pages_home.build()
pages_shop.build_shop()
pages_shop.build_products()
pages_about.build()
pages_portfolio.build()
pages_contact.build()
pages_legal.build()
pages_account.build_cart()
pages_account.build_account()
pages_checkout.build()
pages_accessibility.build()

print("\nGotowe — wygenerowano wszystkie strony.")
