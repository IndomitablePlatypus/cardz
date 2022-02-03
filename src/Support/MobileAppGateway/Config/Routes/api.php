<?php

use Cardz\Support\MobileAppGateway\Config\Routes\RouteName;
use Cardz\Support\MobileAppGateway\Presentation\Controllers\Http\Customer\CustomerController;
use Cardz\Support\MobileAppGateway\Presentation\Controllers\Http\Workspace\CardController;
use Cardz\Support\MobileAppGateway\Presentation\Controllers\Http\Workspace\CollaborationController;
use Cardz\Support\MobileAppGateway\Presentation\Controllers\Http\Workspace\PlanController;
use Cardz\Support\MobileAppGateway\Presentation\Controllers\Http\Workspace\WorkspaceController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => '/api/v1', 'middleware' => 'api'], function () {
    Route::post('/customer/get-token', [CustomerController::class, 'getToken'])->name(RouteName::GET_TOKEN);
    Route::post('/customer/register', [CustomerController::class, 'register'])->name(RouteName::REGISTER);

    Route::get('/customer/workspaces', [CustomerController::class, 'getWorkspaces'])->name(RouteName::CUSTOMER_WORKSPACES);

    Route::group(['middleware' => ['auth:sanctum', 'authorization.mag']], function () {
        Route::group(['prefix' => '/customer'], function () {
            Route::get('/wipe-tokens', [CustomerController::class, 'clearTokens'])->name(RouteName::CLEAR_TOKENS);
            Route::get('/id', [CustomerController::class, 'getId'])->name(RouteName::CUSTOMER_ID);
            Route::get('/profile', [CustomerController::class, 'getProfile'])->name(RouteName::CUSTOMER_PROFILE);
            Route::get('/card', [CustomerController::class, 'getCards'])->name(RouteName::CUSTOMER_CARDS);
            Route::get('/card/{cardId}', [CustomerController::class, 'getCard'])->name(RouteName::CUSTOMER_CARD);
        });

        Route::get('/workspace', [WorkspaceController::class, 'getWorkspaces'])->name(RouteName::GET_WORKSPACES);
        Route::post('/workspace', [WorkspaceController::class, 'addWorkspace'])->name(RouteName::ADD_WORKSPACE);

        Route::group(['prefix' => '/workspace/{workspaceId}'], function () {
            Route::get('/', [WorkspaceController::class, 'getWorkspace'])->name(RouteName::GET_WORKSPACE);
            Route::put('/profile', [WorkspaceController::class, 'changeWorkspaceProfile'])->name(RouteName::CHANGE_PROFILE);

            Route::group(['prefix' => '/collaboration'], function () {
                Route::post('/leave', [CollaborationController::class, 'leave'])->name(RouteName::LEAVE_RELATION);
                Route::post('/fire/{collaboratorId}', [CollaborationController::class, 'fire'])->name(RouteName::FIRE_COLLABORATOR);

                Route::post('/invite', [CollaborationController::class, 'propose'])->name(RouteName::PROPOSE_INVITE);
                Route::put('/invite/{inviteId}/accept', [CollaborationController::class, 'accept'])->name(RouteName::ACCEPT_INVITE);
                Route::delete('/invite/{inviteId}/discard', [CollaborationController::class, 'discard'])->name(RouteName::DISCARD_INVITE);
            });

            Route::group(['prefix' => '/plan'], function () {
                Route::get('/', [PlanController::class, 'getWorkspaceBusinessPlans'])->name(RouteName::GET_PLANS);
                Route::post('/', [PlanController::class, 'add'])->name(RouteName::ADD_PLAN);
            });

            Route::group(['prefix' => '/plan/{planId}'], function () {
                Route::get('/', [PlanController::class, 'getPlan'])->name(RouteName::GET_PLAN);

                Route::put('/profile', [PlanController::class, 'changeProfile'])->name(RouteName::CHANGE_PLAN_PROFILE);
                Route::put('/launch', [PlanController::class, 'launch'])->name(RouteName::LAUNCH_PLAN);
                Route::put('/stop', [PlanController::class, 'stop'])->name(RouteName::STOP_PLAN);
                Route::put('/archive', [PlanController::class, 'archive'])->name(RouteName::ARCHIVE_PLAN);

                Route::post('/requirement', [PlanController::class, 'addRequirement'])->name(RouteName::ADD_PLAN_REQUIREMENT);
                Route::delete('/requirement/{requirementId}', [PlanController::class, 'removeRequirement'])->name(RouteName::REMOVE_PLAN_REQUIREMENT);
                Route::put('/requirement/{requirementId}', [PlanController::class, 'changeRequirement'])->name(RouteName::CHANGE_PLAN_REQUIREMENT);
            });

            Route::post('/card', [CardController::class, 'issue'])->name(RouteName::ISSUE_CARD);

            Route::group(['prefix' => '/card/{cardId}'], function () {
                Route::get('/', [CardController::class, 'getCard'])->name(RouteName::GET_CARD);

                Route::put('/complete', [CardController::class, 'complete'])->name(RouteName::COMPLETE_CARD);
                Route::put('/revoke', [CardController::class, 'revoke'])->name(RouteName::REVOKE_CARD);
                Route::put('/block', [CardController::class, 'block'])->name(RouteName::BLOCK_CARD);
                Route::put('/unblock', [CardController::class, 'unblock'])->name(RouteName::UNBLOCK_CARD);

                Route::post('/achievement', [CardController::class, 'noteAchievement'])->name(RouteName::NOTE_ACHIEVEMENT);
                Route::delete('/achievement/{achievementId}', [CardController::class, 'dismissAchievement'])->name(RouteName::DISMISS_ACHIEVEMENT);
            });

        });
    });
});
