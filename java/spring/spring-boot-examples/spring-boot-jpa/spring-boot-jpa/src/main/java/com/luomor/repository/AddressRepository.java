package com.luomor.repository;

import com.luomor.model.Address;
import org.springframework.data.jpa.repository.JpaRepository;

public interface AddressRepository extends JpaRepository<Address, Long> {
}