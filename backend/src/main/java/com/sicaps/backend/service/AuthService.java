package com.sicaps.backend.service;

import com.sicaps.backend.dto.AuthResponse;
import com.sicaps.backend.dto.LoginRequest;
import com.sicaps.backend.entity.User;
import com.sicaps.backend.repository.UserRepository;
import com.sicaps.backend.security.JwtService;
import lombok.RequiredArgsConstructor;
import org.springframework.security.authentication.AuthenticationManager;
import org.springframework.security.authentication.UsernamePasswordAuthenticationToken;
import org.springframework.stereotype.Service;

@Service
@RequiredArgsConstructor
public class AuthService {

    private final UserRepository userRepository;
    private final JwtService jwtService;
    private final AuthenticationManager authenticationManager;

    public AuthResponse login(LoginRequest request) {
        String email = request.getEmail() != null ? request.getEmail().trim() : "";
        authenticationManager.authenticate(
                new UsernamePasswordAuthenticationToken(
                        email,
                        request.getPassword()
                )
        );
        var user = userRepository.findByEmailIgnoreCase(email)
                .orElseThrow();
        var jwtToken = jwtService.generateToken(user);
        
        String fullName = (user.getName() != null ? user.getName() : "") + 
                (user.getLastName() != null ? " " + user.getLastName() : "");
        
        return AuthResponse.builder()
                .token(jwtToken)
                .email(user.getEmail())
                .role(user.getRole().getName().name())
                .name(fullName.trim().isEmpty() ? user.getEmail() : fullName.trim())
                .build();
    }
}
