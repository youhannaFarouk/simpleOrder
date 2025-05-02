<?php
namespace App\Services\Interfaces;

interface IOrderServices
{
  public function createOrder(array $data): array;
  public function updateOrder(int $id, array $data): array;
  public function getAllOrders(): array;
  public function getOrderStats(): array;
}