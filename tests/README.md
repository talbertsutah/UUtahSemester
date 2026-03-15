# UUtah Semester Tests

This directory contains the comprehensive test suite for the UUtah Semester package.

## Test Organization

The test suite is organized into focused test classes:

- **SemesterConstructorTest** - Tests for object construction and validation
- **SemesterConversionTest** - Tests for code ↔ string conversions and validation methods
- **SemesterArithmeticTest** - Tests for addition/subtraction of semesters and years
- **SemesterComparisonTest** - Tests for comparison methods (isBefore, isAfter, equals, getDifference)
- **SemesterSerializationTest** - Tests for toArray() and toJson() serialization methods
- **SemesterRangeTest** - Tests for getSemestersBetween() range generation
- **SemesterStaticMethodsTest** - Tests for static methods (now, next, previous, random, isValid*)

## Running the Tests

### Prerequisites

First, install dev dependencies:

```bash
composer install
```

### Run All Tests

```bash
./vendor/bin/phpunit
```

### Run a Specific Test Class

```bash
./vendor/bin/phpunit tests/SemesterConstructorTest.php
```

### Run a Specific Test Method

```bash
./vendor/bin/phpunit tests/SemesterConstructorTest.php --filter testConstructorWithValidCode
```

### Run with Coverage Report

```bash
./vendor/bin/phpunit --coverage-html coverage
```

This generates an HTML coverage report in the `coverage/` directory.

### Run with Verbose Output

```bash
./vendor/bin/phpunit -v
```

### Run Tests Matching a Pattern

```bash
./vendor/bin/phpunit --filter Conversion
```

## Test Coverage

The test suite covers:

- **70+** test cases across all major functionality
- Constructor validation and exception handling
- All conversion methods with edge cases
- Arithmetic operations including chaining
- Comparison operations across all input types
- Serialization and deserialization
- Range/batch operations
- Static utility methods
- Error handling and exception throwing

## Adding New Tests

When adding new functionality to the Semester class:

1. Create or update the appropriate test class
2. Add test methods following the naming convention: `test<Functionality>`
3. Use descriptive test method names that explain what is being tested
4. Include docblocks explaining the purpose of each test
5. Run `./vendor/bin/phpunit` to ensure all tests pass

## Test Conventions

- All test classes extend `PHPUnit\Framework\TestCase`
- Test methods are public and start with `test`
- Each test should test a single behavior
- Use descriptive assertion messages for clarity
- Test both happy paths and error conditions
- Include edge cases and boundary conditions
