# Contributing to SimpleCache

First of all, thank you for considering contributing to **SimpleCache**! Your contributions help improve the project for everyone.

Whether you're fixing a bug, improving documentation, adding tests, or implementing a new feature, your help is greatly appreciated.

---

# Code of Conduct

Please be respectful and constructive when interacting with other contributors.

We aim to maintain a welcoming and friendly community.

---

# Getting Started

## Requirements

Before contributing, ensure you have:

* PHP 8.2 or later
* Composer
* Git

Optional:

* APCu extension (for APCu driver testing)

---

# Clone the Repository

```bash
git clone https://github.com/<your-username>/SimpleCache.git
cd SimpleCache
```

---

# Install Dependencies

```bash
composer install
```

---

# Project Structure

```text
SimpleCache/
│
├── src/                # Library source code
├── tests/              # PHPUnit tests
├── benchmarks/         # Performance benchmarks
├── docs/               # Documentation
│
├── composer.json
├── phpunit.xml
├── phpstan.neon
├── .php-cs-fixer.php
└── README.md
```

---

# Development Workflow

1. Fork the repository.
2. Create a new branch.

```bash
git checkout -b feature/my-feature
```

3. Make your changes.
4. Add or update tests.
5. Run all quality checks.
6. Commit your changes.
7. Push your branch.
8. Open a Pull Request.

---

# Running Tests

Run the complete test suite.

```bash
composer test
```

---

# Static Analysis

Run PHPStan.

```bash
composer analyse
```

There should be **zero PHPStan errors** before submitting a Pull Request.

---

# Code Style

Run PHP CS Fixer.

```bash
composer fix
```

To verify formatting without making changes:

```bash
composer cs
```

---

# Run All Quality Checks

Before submitting a Pull Request, always run:

```bash
composer check
```

This command should:

* Verify coding standards
* Run PHPStan
* Execute the PHPUnit test suite

Every command should complete successfully.

---

# Coding Standards

Please follow these guidelines:

* Use `declare(strict_types=1);`
* Follow PSR-12 coding standards
* Keep methods small and focused
* Prefer dependency injection where appropriate
* Avoid duplicated code
* Write descriptive variable and method names
* Add PHPDoc for public APIs where appropriate

---

# Testing

Every new feature should include tests.

Whenever possible, include:

* Normal usage
* Edge cases
* Invalid input
* Exception handling

The project uses **PHPUnit** for testing.

---

# Adding New Features

When adding new functionality:

* Keep backward compatibility whenever possible.
* Update the README if public behavior changes.
* Update the CHANGELOG.
* Include unit tests.

---

# Commit Messages

Use clear and descriptive commit messages.

Examples:

```text
Add APCu increment support

Fix FileDriver TTL expiration

Improve Serializer validation

Refactor DriverResolver

Add PHPUnit tests for CacheFactory
```

Avoid messages such as:

```text
fix

update

changes
```

---

# Pull Requests

Before opening a Pull Request, verify that:

* All tests pass
* PHPStan reports no errors
* Code is PSR-12 compliant
* Documentation has been updated (if required)
* CHANGELOG has been updated (if applicable)

Please keep Pull Requests focused on a single feature or bug fix.

---

# Reporting Bugs

When reporting a bug, please include:

* PHP version
* Operating System
* Driver used (Array, File, APCu)
* Steps to reproduce
* Expected behavior
* Actual behavior
* Error messages or stack traces

---

# Feature Requests

Feature requests are welcome.

Please describe:

* The problem you're trying to solve
* Your proposed solution
* Possible alternatives

---

# Security

Please do **not** report security vulnerabilities through public GitHub Issues.

Instead, follow the instructions in **SECURITY.md**.

---

# License

By contributing to this project, you agree that your contributions will be licensed under the MIT License.

---

Thank you for contributing to **SimpleCache**!
