# Orders API

This is a microservice for managing orders in the 2026 test task system.

## Overview

- Built with Symfony 8
- Uses CQRS pattern with Messenger for commands and queries
- Doctrine ORM for persistence
- API documentation via NelmioApiDoc
- Testing with PHPUnit
- Shared contracts from `test-task-shared/core-contracts`

## Key Features

- Create new orders
- Retrieve list of orders
- Get detailed information about a specific order

## Setup

Follow the instructions in the orchestrator repository:  
https://github.com/BonBonSlick/2026_test_task_orchestrator_ms_api

## API Endpoints

- `POST /order/create` - Create a new order
- `GET /order/list` - Get list of orders
- `GET /order/{id}` - Get order by ID
